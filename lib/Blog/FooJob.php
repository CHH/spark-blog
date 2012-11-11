<?php

namespace Blog;

class FooJob extends \Spark\Core\Job
{
    function run()
    {
        var_dump($this->foo);

        echo "Doing some heavy processing...\n";

        sleep(5);

        echo "Finished!\n";
    }
}
