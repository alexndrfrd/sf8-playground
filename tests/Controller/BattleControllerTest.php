<?php

namespace App\Tests\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\ValueObject\CharacterStats;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BattleControllerTest extends WebTestCase
{

    public function testBattlePageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/battle');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testBattlePageShowsCharacters(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/battle');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testCanStartBattleViaPost(): void
    {
        $client = static::createClient();
        
        // Create test characters
        $stats1 = new CharacterStats(20, 10);
        $stats2 = new CharacterStats(10, 20);
        $character1 = new Character('TestWarrior1', $stats1);
        $character2 = new Character('TestWarrior2', $stats2);
        
        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->persist($character1);
        $em->persist($character2);
        $em->flush();
        
        $crawler = $client->request('POST', '/battle/fight', [
            'character1_id' => $character1->getId(),
            'character2_id' => $character2->getId(),
        ]);
        
        $this->assertResponseIsSuccessful();
        
        // Cleanup
        $em->remove($character1);
        $em->remove($character2);
        $em->flush();
    }

    public function testBattleResultShowsWinner(): void
    {
        $client = static::createClient();
        
        $stats1 = new CharacterStats(20, 10);
        $stats2 = new CharacterStats(10, 20);
        $character1 = new Character('TestWarrior1', $stats1);
        $character2 = new Character('TestWarrior2', $stats2);
        
        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->persist($character1);
        $em->persist($character2);
        $em->flush();
        
        $crawler = $client->request('POST', '/battle/fight', [
            'character1_id' => $character1->getId(),
            'character2_id' => $character2->getId(),
        ]);
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Winner');
        
        // Cleanup
        $em->remove($character1);
        $em->remove($character2);
        $em->flush();
    }
}

