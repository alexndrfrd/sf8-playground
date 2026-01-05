<?php

namespace App\Tests\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\ValueObject\CharacterStats;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    public function testCreateCharacterPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/character/create');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateCharacterPageShowsForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/character/create');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="character[name]"]');
        $this->assertSelectorExists('input[name="character[strength]"]');
        $this->assertSelectorExists('input[name="character[intelligence]"]');
        $this->assertSelectorExists('input[name="character[health]"]');
    }

    public function testCanCreateCharacterViaPost(): void
    {
        $client = static::createClient();
        
        // Use proper form parameter format
        $crawler = $client->request('POST', '/character/store', [], [], [
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ], http_build_query([
            'character' => [
                'name' => 'TestWarrior',
                'strength' => 20,
                'intelligence' => 15,
                'health' => 100,
            ],
        ]));
        
        // Should redirect after creation
        $this->assertResponseRedirects();
        
        // Follow redirect
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testCreateCharacterValidatesName(): void
    {
        $client = static::createClient();
        
        $crawler = $client->request('POST', '/character/store', [], [], [
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ], http_build_query([
            'character' => [
                'name' => '',
                'strength' => 20,
                'intelligence' => 15,
                'health' => 100,
            ],
        ]));
        
        // Should redirect back with error
        $this->assertResponseRedirects();
    }

    public function testCreateCharacterValidatesStats(): void
    {
        $client = static::createClient();
        
        $crawler = $client->request('POST', '/character/store', [], [], [
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ], http_build_query([
            'character' => [
                'name' => 'TestWarrior',
                'strength' => -5,
                'intelligence' => 15,
                'health' => 100,
            ],
        ]));
        
        // Should redirect back with error
        $this->assertResponseRedirects();
    }
}

