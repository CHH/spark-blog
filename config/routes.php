<?php

$app['controllers']->draw(function($routes) {
    $routes->get('/api', 'api#index');
    $routes->get('/', 'posts#index')->bind('home');

    $routes->resources("posts");

    $routes->match('/{controller}/{action}/{id}', null)
        ->value('controller', 'index')
        ->value('action', 'index')
        ->value('id', null)
        ->value('format', null)
        ->bind('default');
});

