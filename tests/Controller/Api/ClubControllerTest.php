<?php

namespace App\tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClubControllerTest extends WebTestCase
{
    public function testCreateClub()
    {
        $client = static::createClient();

        $client->request('POST', '/api/club');
    
        $this->assertResponseStatusCodeSame(400, $client->getResponse()->getStatusCode());
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
        $this->assertResponseStatusCodeSame(400, $client->getResponse()->getStatusCode());
    }    
}

