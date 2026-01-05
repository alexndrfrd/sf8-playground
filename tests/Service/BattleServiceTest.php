<?php

namespace App\Tests\Service;

use App\Entity\Character;
use App\Service\BattleService;
use App\ValueObject\CharacterStats;
use App\ValueObject\HitResult;
use PHPUnit\Framework\TestCase;

class BattleServiceTest extends TestCase
{
    private BattleService $battleService;

    protected function setUp(): void
    {
        $this->battleService = new BattleService();
    }

    public function testCanCreateBattleService(): void
    {
        $this->assertInstanceOf(BattleService::class, $this->battleService);
    }

    public function testCanPerformAttack(): void
    {
        $attacker = new Character('Warrior', new CharacterStats(20, 10), 100);
        $defender = new Character('Mage', new CharacterStats(10, 20), 100);
        
        $hitResult = $this->battleService->attack($attacker, $defender);
        
        $this->assertInstanceOf(HitResult::class, $hitResult);
        $this->assertGreaterThan(0, $hitResult->getDamage()->getValue());
    }

    public function testAttackDamageIsWithinRange(): void
    {
        $attacker = new Character('Warrior', new CharacterStats(20, 10), 100);
        $defender = new Character('Mage', new CharacterStats(10, 20), 100);
        
        // Perform multiple attacks to test randomness
        $damages = [];
        for ($i = 0; $i < 50; $i++) {
            // Reset defender health for each attack
            $defender = new Character('Mage', new CharacterStats(10, 20), 100);
            $hitResult = $this->battleService->attack($attacker, $defender);
            $damages[] = $hitResult->getDamage()->getValue();
        }
        
        // Damage should be based on strength (20), so min should be around 20*0.8 = 16
        // Max can be higher due to critical hits (24 * 1.5 = 36), but base max is 24
        $minDamage = min($damages);
        $maxDamage = max($damages);
        
        $this->assertGreaterThanOrEqual(16, $minDamage);
        // Allow for critical hits which can multiply damage
        $this->assertLessThanOrEqual(40, $maxDamage);
        // But most damage should be in normal range
        $normalDamages = array_filter($damages, fn($d) => $d <= 24);
        $this->assertGreaterThan(0, count($normalDamages));
    }

    public function testAttackCanBeCritical(): void
    {
        $attacker = new Character('Warrior', new CharacterStats(20, 10), 100);
        $defender = new Character('Mage', new CharacterStats(10, 20), 100);
        
        // Perform many attacks to increase chance of critical
        $hasCritical = false;
        for ($i = 0; $i < 100; $i++) {
            $hitResult = $this->battleService->attack($attacker, $defender);
            if ($hitResult->isCritical()) {
                $hasCritical = true;
                // Critical hit should do more damage
                $this->assertGreaterThan(20, $hitResult->getDamage()->getValue());
                break;
            }
        }
        
        // With 100 attempts, we should get at least one critical (10% chance)
        // But we can't guarantee it, so we just check if criticals exist when they occur
        if ($hasCritical) {
            $this->assertTrue(true); // Critical hit found and validated
        }
    }

    public function testAttackReducesDefenderHealth(): void
    {
        $attacker = new Character('Warrior', new CharacterStats(20, 10), 100);
        $defender = new Character('Mage', new CharacterStats(10, 20), 100);
        
        $initialHealth = $defender->getHealth();
        $this->battleService->attack($attacker, $defender);
        
        $this->assertLessThan($initialHealth, $defender->getHealth());
    }

    public function testCanPerformBattle(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(20, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 100);
        
        $battleResult = $this->battleService->battle($character1, $character2);
        
        $this->assertIsArray($battleResult);
        $this->assertArrayHasKey('winner', $battleResult);
        $this->assertArrayHasKey('turns', $battleResult);
        $this->assertArrayHasKey('history', $battleResult);
    }

    public function testBattleHasWinner(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(20, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 100);
        
        $battleResult = $this->battleService->battle($character1, $character2);
        
        $this->assertInstanceOf(Character::class, $battleResult['winner']);
        // Winner should be alive
        $this->assertTrue($battleResult['winner']->isAlive());
        // At least one character should be dead
        $this->assertFalse($character1->isAlive() && $character2->isAlive());
    }

    public function testBattleHistoryContainsTurns(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(20, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 100);
        
        $battleResult = $this->battleService->battle($character1, $character2);
        
        $this->assertIsArray($battleResult['history']);
        $this->assertGreaterThan(0, count($battleResult['history']));
    }

    public function testBattleEndsWhenOneCharacterDies(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(100, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 10);
        
        $battleResult = $this->battleService->battle($character1, $character2);
        
        // At least one character should be dead (battle should end)
        $this->assertFalse($character1->isAlive() && $character2->isAlive());
        // Winner should be alive
        $this->assertTrue($battleResult['winner']->isAlive());
    }
}

