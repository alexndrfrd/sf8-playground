<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HeroControllerTest extends WebTestCase
{
    public function testCreateHeroEndpointExists(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/hero/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode(['name' => 'Test Hero']));

        $this->assertResponseIsSuccessful();
    }

    public function testCreateHeroReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/hero/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode(['name' => 'Test Hero']));

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testCreateHeroWithName(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/hero/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode(['name' => 'Warrior']));

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Warrior', $data['name']);
    }

    public function testCreateHeroHasAllAttributes(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/hero/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode(['name' => 'Mage']));

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('strength', $data);
        $this->assertArrayHasKey('intelligence', $data);
        $this->assertArrayHasKey('speed', $data);
        $this->assertArrayHasKey('attackSpeed', $data);
        $this->assertArrayHasKey('damage', $data);
        $this->assertArrayHasKey('spells', $data);
    }

    public function testCreateHeroHasFourSpells(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/hero/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode(['name' => 'Rogue']));

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data['spells']);
        $this->assertCount(4, $data['spells']);
    }

    public function testCreateHeroRequiresName(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/hero/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([]));

        $this->assertResponseStatusCodeSame(400);
    }

    public function testCreateHeroNameCannotBeEmpty(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/hero/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode(['name' => '']));

        $this->assertResponseStatusCodeSame(400);
    }
}

