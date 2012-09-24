<?php

$app['controllers']->draw(function($routes) {
    $routes->get('/', 'posts#index')->bind('home');

    $routes->resources("posts");

    $routes->match('/{controller}/{action}/{id}', null)
        ->value('controller', 'index')
        ->value('action', 'index')
        ->value('id', null)
        ->bind('default');
});

