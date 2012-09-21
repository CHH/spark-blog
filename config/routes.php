<?php

$app['controllers']->draw(function($routes) {
    $routes->get('/', 'index#index')->bind('home');
    $routes->get('/hello/{name}', 'index#hello')->bind('hello');
    $routes->get('/search', 'index#search');
    $routes->get('/foo', 'index#foo');
    $routes->get('/flashtest', 'index#flashTest');
    $routes->get('/json', 'index#json');

    $routes->match('/admin/{controller}/{action}', null)
        ->value('module', 'admin')
        ->value('controller', 'index')
        ->value('action', 'index')
        ->bind('admin');

    $routes->match('/{controller}/{action}/{id}', null)
        ->value('controller', 'index')
        ->value('action', 'index')
        ->value('id', null)
        ->bind('default');
});

