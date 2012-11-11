<?php

namespace Blog;

class PostsControllerTest extends \Spark\Core\WebTestCase
{
    function testIndex()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(1, $crawler->filter('section.posts'));
    }
}

