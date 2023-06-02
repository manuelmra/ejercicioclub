<?php

namespace App\tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    public function testCreatePlayer()
    {
        $client = static::createClient();

        $client->request('POST', '/api/player');
    
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreatePlayerInvalidData()
    {
        $client = static::createClient();
 
        $client->request(
            'POST',
            '/api/player',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }    
}

