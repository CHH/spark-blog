<?php

namespace Blog;

class Bootstrap extends \Spark\Core\Bootstrap
{
}

return Bootstrap::bootstrap(__DIR__ . '/..', @$_SERVER['SPARK_ENV'] ?: "development");

