<?php

namespace Movie\MovieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JoindInControllerTest extends WebTestCase
{
    public function testEvents()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/events');
    }

    public function testEvent()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/event');
    }

    public function testTalks()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/talks');
    }

}
