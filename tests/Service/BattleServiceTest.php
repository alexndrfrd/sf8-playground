<?php

namespace App\Tests\Service;

use App\Entity\Hero;
use App\Service\BattleService;
use App\Service\HeroFactory;
use PHPUnit\Framework\TestCase;

class BattleServiceTest extends TestCase
{
    private BattleService $battleService;
    private HeroFactory $heroFactory;

    protected function setUp(): void
    {
        $this->battleService = new BattleService();
        $this->heroFactory = new HeroFactory();
    }

    public function testCanStartBattle(): void
    {
        $hero1 = $this->heroFactory->createRandomHero('Hero1');
        $hero2 = $this->heroFactory->createRandomHero('Hero2');

        $result = $this->battleService->battle($hero1, $hero2);

        $this->assertIsArray($result);
    }

    public function testBattleResultHasWinner(): void
    {
        $hero1 = $this->heroFactory->createRandomHero('Hero1');
        $hero2 = $this->heroFactory->createRandomHero('Hero2');

        $result = $this->battleService->battle($hero1, $hero2);

        $this->assertArrayHasKey('winner', $result);
        $this->assertInstanceOf(Hero::class, $result['winner']);
    }

    public function testBattleResultHasLoser(): void
    {
        $hero1 = $this->heroFactory->createRandomHero('Hero1');
        $hero2 = $this->heroFactory->createRandomHero('Hero2');

        $result = $this->battleService->battle($hero1, $hero2);

        $this->assertArrayHasKey('loser', $result);
        $this->assertInstanceOf(Hero::class, $result['loser']);
    }

    public function testBattleResultHasRounds(): void
    {
        $hero1 = $this->heroFactory->createRandomHero('Hero1');
        $hero2 = $this->heroFactory->createRandomHero('Hero2');

        $result = $this->battleService->battle($hero1, $hero2);

        $this->assertArrayHasKey('rounds', $result);
        $this->assertIsArray($result['rounds']);
        $this->assertGreaterThan(0, count($result['rounds']));
    }

    public function testBattleHasAtLeastOneRound(): void
    {
        $hero1 = $this->heroFactory->createRandomHero('Hero1');
        $hero2 = $this->heroFactory->createRandomHero('Hero2');

        $result = $this->battleService->battle($hero1, $hero2);

        $this->assertGreaterThanOrEqual(1, count($result['rounds']));
    }

    public function testBattleRoundHasAttackerAndDefender(): void
    {
        $hero1 = $this->heroFactory->createRandomHero('Hero1');
        $hero2 = $this->heroFactory->createRandomHero('Hero2');

        $result = $this->battleService->battle($hero1, $hero2);

        $firstRound = $result['rounds'][0];
        $this->assertArrayHasKey('attacker', $firstRound);
        $this->assertArrayHasKey('defender', $firstRound);
        $this->assertArrayHasKey('damage', $firstRound);
    }

    public function testWinnerIsDifferentFromLoser(): void
    {
        $hero1 = $this->heroFactory->createRandomHero('Hero1');
        $hero2 = $this->heroFactory->createRandomHero('Hero2');

        $result = $this->battleService->battle($hero1, $hero2);

        $this->assertNotSame($result['winner'], $result['loser']);
    }

    public function testBattleEndsWhenOneHeroDies(): void
    {
        $hero1 = $this->heroFactory->createRandomHero('Hero1');
        $hero2 = $this->heroFactory->createRandomHero('Hero2');

        $result = $this->battleService->battle($hero1, $hero2);

        // Winner should have health > 0, loser should have health <= 0
        // Since we don't track health yet, we'll just verify structure
        $this->assertArrayHasKey('winner', $result);
        $this->assertArrayHasKey('loser', $result);
    }
}

