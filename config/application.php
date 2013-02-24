<?php

namespace Blog;

use Doctrine\Common\Cache\ApcCache;

$app['spark.app.name'] = 'Blog';

# Override the default class which is used as View Context, default is 
# "$AppName\ViewContext"
# $app['spark.action_pack.view_context_class'] = '\MyApp\ViewContext';

$app['monolog.logfile'] = '/tmp/spark-app.log';

# All your controller and model classes are loaded from this namespaces. By
# default its set to your app name.
#
# $app['spark.default_module'] = "MyApp";

# This is the base class which should be extended by all your controllers.
class ApplicationController extends \Spark\ActionPack\Controller\Base
{
    # Include Action Helper traits here to make them available
    # in all your controllers.
}

class ViewContext extends \Spark\ActionPack\ViewContext
{
    # Include your custom view helpers here.
}

require __DIR__ . '/pipe.php';
require __DIR__ . '/database.php';

