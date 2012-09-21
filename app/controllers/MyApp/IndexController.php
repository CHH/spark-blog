<?php

namespace MyApp;

class IndexController extends ApplicationController
{
    function setup()
    {
        $this->beforeFilter("checkLogin");
    }

    protected function checkLogin()
    {
        if ($this->request()->attributes->get('action') === 'search') {
            return $this->redirect('home');
        }
    }

    function index()
    {
    }

    function json()
    {
        $json = ['result' => ['foo' => 'bar'], 'success' => true];

        return $this->render(['json' => $json, 'pretty' => true]);
    }

    function hello($name)
    {
        $this->name = $name;
        $this->flash()->add('notice', "Foo");
    }

    function foo()
    {
        return $this->redirect('hello', ['params' => ['name' => 'John Doe']]);
    }

    function search()
    {
        return $this->redirect("http://google.at");
    }

    function flashTest()
    {
        $this->flash()->add('notice', "Foo");
        # return $this->redirect('home');
        return $this->redirect(['controller' => 'index']);
    }
}
