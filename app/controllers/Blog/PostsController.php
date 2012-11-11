<?php

namespace Blog;

use dflydev\markdown\MarkdownExtraParser;

class PostsController extends ApplicationController
{
    function indexAction()
    {
        $posts = $this->posts()->find()->sort(['created' => -1]);

        # Setting a closure as value makes the property a computed property.
        # The closure is evaluated and the result is cached when the property is
        # first accessed in the view.
        $this->posts = function() use ($posts) {
            $postsByDate = [];

            foreach ($posts as $post) {
                $date = new \DateTime();
                $date->setTimestamp($post['created']->sec);

                $postsByDate[$date->format('M d Y')][] = $post;
            }

            return $postsByDate;
        };
    }

    function newAction()
    {
    }

    function createAction()
    {
        # Whitelist 'title' and 'content' keys
        $post = array_intersect_key($this->request()->request->get('post'), array_flip(['title', 'content']));
        $post['slug'] = $this->slugify($post['title']);
        $post['created'] = new \MongoDate();

        $this->posts()->save($post);

        $this->flash()->add('notice', 'Post created.');

        return $this->redirect("/posts/{$post['_id']}");
    }

    function showAction($id)
    {
        if (!$this->post = $this->posts()->findOne(['_id' => new \MongoId($id)])) {
            return $this->notFound();
        }

        $md = new MarkdownExtraParser;

        $this->post['content'] = $md->transformMarkdown($this->post['content']);
    }

    function editAction($id)
    {
        if (!$this->post = $this->posts()->findOne(['_id' => new \MongoId($id)])) {
            return $this->notFound();
        }
    }

    function updateAction($id)
    {
        $posts = $this->posts();

        if (!$this->post = $posts->findOne(['_id' => new \MongoId($id)])) {
            return $this->notFound();
        }

        # Allow the user to only set title and content
        $post = array_intersect_key($this->request()->get('post'), array_flip(['title', 'content']));
        $post['slug'] = $this->slugify($post['title']);

        $this->post = array_merge($this->post, $post);
        $this->post['updated'] = new \MongoDate();

        $posts->save($this->post);

        $this->flash()->add('notice', "Post saved.");
        return $this->redirect('posts_show', ['params' => ['id' => (string) $this->post['_id']]]);
    }

    function destroyAction($id)
    {
        $this->posts()->remove(['_id' => new \MongoId($id)]);

        $this->flash()->add('notice', "Post removed.");

        return $this->redirect(['action' => 'index']);
    }

    function permalinkAction($slug)
    {
        if (!$this->post = $this->posts()->findOne(['slug' => $slug])) {
            return $this->notFound();
        }

        return $this->forward('posts_show', ['params' => ['id' => (string) $this->post['_id']]]);
    }

    protected function posts()
    {
        return $this->application['db']->posts;
    }

    protected function slugify($title)
    {
        $slug = str_replace([' '], '-', $title);
        $slug = strtolower($slug);
        return $slug;
    }
}
