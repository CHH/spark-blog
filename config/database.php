<?php

$app['mongo.host'] = 'mongodb://localhost:27017';
$app['mongo.db'] = 'blog';

$app['mongo'] = $app->share(function($app) {
    return new Mongo($app['mongo.host']);
});

$app['db'] = $app->share(function($app) {
    return $app['mongo']->{$app['mongo.db']};
});

