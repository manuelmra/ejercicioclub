<?php

namespace App\tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoachControllerTest extends WebTestCase
{
    public function testCreateCoach()
    {
        $client = static::createClient();

        $client->request('POST', '/api/coach');
    
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateCoachInvalidData()
    {
        $client = static::createClient();
 
        $client->request(
            'POST',
            '/api/coach',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }    
}

