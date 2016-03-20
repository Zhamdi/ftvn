<?php

namespace AppBundle\Tests\Controller;

use Bazinga\Bundle\RestExtraBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function setUp()
    {
        $cacheDir = $this->getClient()->getContainer()->getParameter('kernel.cache_dir');
        if (file_exists($cacheDir.'/sf_note_data')) {
            unlink($cacheDir.'/sf_note_data');
        }
    }

    public function testGetArticles()
    {
        $client = $this->getClient(true);
        $client->request('GET', '/articles.json');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
    }

    public function testGetArticle()
    {
        $client = $this->getClient(true);

        $client->request('GET', '/articles/0.json');
        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
    }

    public function testNewArticle()
    {
        $client = $this->getClient(true);

        $client->request('GET', '/articles/new.json');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $this->assertEquals('{"children":{"title":{},"leadin":{},"body":{},"createdBy":{}}}', $response->getContent());
    }

    private function getClient($authenticated = false)
    {
        $params = array();
        if ($authenticated) {
            $params = array_merge($params, array(
                    'PHP_AUTH_USER' => 'restapi',
                    'PHP_AUTH_PW' => 'secretpw',
                ));
        }

        return static::createClient(array(), $params);
    }
}
