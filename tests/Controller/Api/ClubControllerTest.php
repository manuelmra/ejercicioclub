<?php

namespace App\tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClubControllerTest extends WebTestCase
{
    public function testCreateClub()
    {
        $client = static::createClient();

        $client->request('POST', '/api/club');
    
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateClubInvalidData()
    {
        $client = static::createClient();
 
        $client->request(
            'POST',
            '/api/club',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }    
}

