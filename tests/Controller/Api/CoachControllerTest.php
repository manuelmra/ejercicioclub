<?php

namespace App\tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoachControllerTest extends WebTestCase
{
    public function testCreateCoach()
    {
        $client = static::createClient();

        $client->request('POST', '/api/coach');
    
        $this->assertResponseStatusCodeSame(400, $client->getResponse()->getStatusCode());
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
        $this->assertResponseStatusCodeSame(400, $client->getResponse()->getStatusCode());
    }    
}

