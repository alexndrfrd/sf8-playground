<?php

namespace App\Tests\Service\Battle;

use App\Entity\Character;
use App\Service\Battle\BattleResultProcessor;
use App\ValueObject\CharacterStats;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class BattleResultProcessorTest extends TestCase
{
    private BattleResultProcessor $processor;
    private Session $session;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->processor = new BattleResultProcessor();
    }

    public function testCanCreateProcessor(): void
    {
        $this->assertInstanceOf(BattleResultProcessor::class, $this->processor);
    }

    public function testStoresBattleResultInSession(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(20, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 100);
        
        // Use reflection to set IDs for testing
        $reflection1 = new \ReflectionClass($character1);
        $idProperty1 = $reflection1->getProperty('id');
        $idProperty1->setAccessible(true);
        $idProperty1->setValue($character1, 1);
        
        $reflection2 = new \ReflectionClass($character2);
        $idProperty2 = $reflection2->getProperty('id');
        $idProperty2->setAccessible(true);
        $idProperty2->setValue($character2, 2);
        
        $battleResult = [
            'winner' => $character1,
            'turns' => 5,
            'history' => [
                ['turn' => 1, 'attacker' => 'Warrior', 'defender' => 'Mage', 'damage' => 20, 'isCritical' => false, 'defenderHealth' => 80],
            ],
        ];

        $this->processor->storeResult($this->session, $character1, $character2, $battleResult);

        $stored = $this->session->get('battle_result');
        $this->assertIsArray($stored);
        $this->assertEquals(1, $stored['character1_id']);
        $this->assertEquals(2, $stored['character2_id']);
        $this->assertEquals('Warrior', $stored['winner_name']);
        $this->assertEquals(5, $stored['turns']);
        $this->assertIsArray($stored['history']);
    }

    public function testRetrievesBattleResultFromSession(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(20, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 100);

        // Use reflection to set IDs for testing
        $reflection1 = new \ReflectionClass($character1);
        $idProperty1 = $reflection1->getProperty('id');
        $idProperty1->setAccessible(true);
        $idProperty1->setValue($character1, 1);
        
        $reflection2 = new \ReflectionClass($character2);
        $idProperty2 = $reflection2->getProperty('id');
        $idProperty2->setAccessible(true);
        $idProperty2->setValue($character2, 2);

        $this->session->set('battle_result', [
            'character1_id' => 1,
            'character2_id' => 2,
            'winner_name' => 'Warrior',
            'turns' => 5,
            'history' => [
                ['turn' => 1, 'attacker' => 'Warrior', 'defender' => 'Mage', 'damage' => 20, 'isCritical' => false, 'defenderHealth' => 80],
            ],
        ]);

        $result = $this->processor->retrieveResult($this->session, $character1, $character2);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('winner', $result);
        $this->assertArrayHasKey('turns', $result);
        $this->assertArrayHasKey('history', $result);
        $this->assertEquals(5, $result['turns']);
    }

    public function testRetrieveResultReturnsNullWhenNotFound(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(20, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 100);

        $result = $this->processor->retrieveResult($this->session, $character1, $character2);

        $this->assertNull($result);
    }

    public function testRetrieveResultReturnsNullWhenCharacterIdsDontMatch(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(20, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 100);
        
        // Use reflection to set IDs for testing
        $reflection1 = new \ReflectionClass($character1);
        $idProperty1 = $reflection1->getProperty('id');
        $idProperty1->setAccessible(true);
        $idProperty1->setValue($character1, 1);
        
        $reflection2 = new \ReflectionClass($character2);
        $idProperty2 = $reflection2->getProperty('id');
        $idProperty2->setAccessible(true);
        $idProperty2->setValue($character2, 2);

        // Store result with character1 and character3 (different from character2)
        $this->session->set('battle_result', [
            'character1_id' => 1,
            'character2_id' => 3, // Different from character2's ID (2)
            'winner_name' => 'Warrior',
            'turns' => 5,
            'history' => [],
        ]);

        $result = $this->processor->retrieveResult($this->session, $character1, $character2);

        $this->assertNull($result);
    }

    public function testClearsBattleResultFromSession(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(20, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 100);

        $this->session->set('battle_result', [
            'character1_id' => $character1->getId(),
            'character2_id' => $character2->getId(),
            'winner_name' => 'Warrior',
            'turns' => 5,
            'history' => [],
        ]);

        $this->processor->clearResult($this->session);

        $this->assertNull($this->session->get('battle_result'));
    }
}

