<?php

namespace App\tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    public function testCreatePlayer()
    {
        $client = static::createClient();

        $client->request('POST', '/api/player');
    
        $this->assertResponseStatusCodeSame(400, $client->getResponse()->getStatusCode());
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
        $this->assertResponseStatusCodeSame(400, $client->getResponse()->getStatusCode());
    }    
}

