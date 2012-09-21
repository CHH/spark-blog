<?php

namespace MyApp;

$app['spark.app.name'] = 'MyApp';
# $app['spark.view_context_class'] = '\MyApp\ViewContext';

# All your controller and model classes are loaded from this namespaces. By
# default its set to your app name.
#
# $app['spark.default_module'] = "MyApp";

# This is the base class which should be extended by all your controllers.
class ApplicationController extends \Spark\Controller\Base
{
    # Include Action Helper traits here to make them available
    # in all your controllers.
}

class ViewContext extends \Spark\Controller\ViewContext
{
    # Include your custom view helpers here.
}

