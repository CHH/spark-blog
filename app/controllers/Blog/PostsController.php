<?php

namespace Blog;

use dflydev\markdown\MarkdownExtraParser;

class PostsController extends ApplicationController
{
    function index()
    {
        $posts = $this->application['db']->posts->find()->sort(['created' => -1]);

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
            $post = $this->request()->get('post');
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

        if ($this->request()->isMethod('POST')) {
            $this->post = $posts->findOne(['_id' => new \MongoId($id)]);

            $this->post = array_merge($this->post, $this->request()->get('post'));
            $this->post['_id'] = new \MongoId($id);
            $this->post['updated'] = new \MongoDate();

            $posts->save($this->post);

            $this->flash()->add('notice', "Post saved.");
            return $this->redirect(['action' => 'show', 'id' => (string) $this->post['_id']]);
        }

        if (!$this->post = $posts->findOne(['_id' => new \MongoId($id)])) {
            return $this->application->abort(404);
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
