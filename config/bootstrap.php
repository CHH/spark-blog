<?php

$app = new \Spark\Application;
$app['spark.root'] = __DIR__ . "/../";
$app['spark.env'] = $env = @$_SERVER["SPARK_ENV"] ?: "production";

require(__DIR__ . "/application.php");
require(__DIR__ . "/routes.php");

if (is_file(__DIR__ . "/environments/$env.php")) {
    require(__DIR__ . "/environments/$env.php");
}

return $app;
