<?php

namespace Blog;

use dflydev\markdown\MarkdownExtraParser;

class PostsController extends ApplicationController
{
    function index()
    {
        $this->posts = $this->application['db']->posts->find();
    }

    function create()
    {
        if ($this->request()->getMethod() === 'POST') {
            $post = $this->request()->get('post');
            $post['created'] = new \MongoDate();

            $this->application['db']->posts->save($post);

            $this->flash()->add('notice', 'Post created.');

            return $this->redirect(['controller' => 'posts', 'action' => 'show', 'id' => (string) $post['_id']]);
        }
    }

    function show($id)
    {
        $db = $this->application['db'];
        $posts = $db->posts;

        if (!$this->post = $posts->findOne(['_id' => new \MongoId($id)])) {
            return $this->render(['status' => 404, 'html' => "<h1>Not Found</h1>"]);
        }

        $md = new MarkdownExtraParser;

        $this->post['content'] = $md->transformMarkdown($this->post['content']);
    }

    function edit($id)
    {
        $posts = $this->application['db']->posts;

        if ($this->request()->getMethod() === 'POST') {
            $this->post = $posts->findOne(['_id' => new \MongoId($id)]);

            $this->post = array_merge($this->post, $this->request()->get('post'));
            $this->post['_id'] = new \MongoId($id);
            $this->post['updated'] = new \MongoDate();

            $posts->save($this->post);

            $this->flash()->add('notice', "Post saved.");
            return $this->redirect(['controller' => 'posts', 'action' => 'show', 'id' => (string) $this->post['_id']]);
        }

        if (!$this->post = $posts->findOne(['_id' => new \MongoId($id)])) {
            return $this->render(['status' => 404, 'html' => "<h1>Not Found</h1>"]);
        }
    }

    function delete($id)
    {
        $posts = $this->application['db']->posts;
        $posts->remove(['_id' => new \MongoId($id)]);

        $this->flash()->add('notice', "Post removed.");

        return $this->redirect(['controller' => 'posts', 'action' => 'index']);
    }

    function permalink($slug)
    {
    }
}
