<?php

namespace Blog;

use dflydev\markdown\MarkdownExtraParser;

class PostsController extends ApplicationController
{
    function index()
    {
        $posts = $this->application['db']->posts->find()->sort(['created' => -1]);

        # There's no magic involved here, it's just that PHP allows creating properties
        # without defining them. The view is then bound to the controller instance, therefore
        # this property is also available in the view.
        #
        # You can define this property by the way, make sure it's public though.
        $this->posts = [];

        foreach ($posts as $post) {
            $date = new \DateTime();
            $date->setTimestamp($post['created']->sec);

            $this->posts[$date->format('M d Y')][] = $post;
        }
    }

    function create()
    {
        if ($this->request()->isMethod('POST')) {
            # Whitelist 'title' and 'content' keys
            $post = array_intersect_key($this->request()->get('post'), array_flip(['title', 'content']));
            $post['created'] = new \MongoDate();

            $this->application['db']->posts->save($post);

            $this->flash()->add('notice', 'Post created.');

            return $this->redirect(['action' => 'show', 'id' => (string) $post['_id']]);
        }
    }

    function show($id)
    {
        $db = $this->application['db'];
        $posts = $db->posts;

        if (!$this->post = $posts->findOne(['_id' => new \MongoId($id)])) {
            return $this->application->abort(404);
        }

        $md = new MarkdownExtraParser;

        $this->post['content'] = $md->transformMarkdown($this->post['content']);
    }

    function edit($id)
    {
        $posts = $this->application['db']->posts;

        if (!$this->post = $posts->findOne(['_id' => new \MongoId($id)])) {
            return $this->application->abort(404);
        }

        if ($this->request()->isMethod('POST')) {
            # Allow the user to only set title and content
            $post = array_intersect_key($this->request()->get('post'), array_flip(['title', 'content']));

            $this->post = array_merge($this->post, $post);
            $this->post['updated'] = new \MongoDate();

            $posts->save($this->post);

            $this->flash()->add('notice', "Post saved.");
            return $this->redirect(['action' => 'show', 'id' => (string) $this->post['_id']]);
        }
    }

    function delete($id)
    {
        $posts = $this->application['db']->posts;
        $posts->remove(['_id' => new \MongoId($id)]);

        $this->flash()->add('notice', "Post removed.");

        return $this->redirect(['action' => 'index']);
    }

    function permalink($slug)
    {
    }
}
