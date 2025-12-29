<?php

namespace App\Tests\Service;

use App\Entity\Hero;
use App\Service\HeroFactory;
use PHPUnit\Framework\TestCase;

class HeroFactoryTest extends TestCase
{
    private HeroFactory $heroFactory;

    protected function setUp(): void
    {
        $this->heroFactory = new HeroFactory();
    }

    public function testCanCreateRandomHero(): void
    {
        $hero = $this->heroFactory->createRandomHero('Test Hero');
        
        $this->assertInstanceOf(Hero::class, $hero);
        $this->assertEquals('Test Hero', $hero->getName());
    }

    public function testRandomHeroHasStrength(): void
    {
        $hero = $this->heroFactory->createRandomHero('Warrior');
        
        $this->assertGreaterThan(0, $hero->getStrength());
    }

    public function testRandomHeroHasIntelligence(): void
    {
        $hero = $this->heroFactory->createRandomHero('Mage');
        
        $this->assertGreaterThan(0, $hero->getIntelligence());
    }

    public function testRandomHeroHasSpeed(): void
    {
        $hero = $this->heroFactory->createRandomHero('Rogue');
        
        $this->assertGreaterThan(0, $hero->getSpeed());
    }

    public function testRandomHeroHasAttackSpeed(): void
    {
        $hero = $this->heroFactory->createRandomHero('Archer');
        
        $this->assertGreaterThan(0.0, $hero->getAttackSpeed());
    }

    public function testRandomHeroHasDamage(): void
    {
        $hero = $this->heroFactory->createRandomHero('Fighter');
        
        $this->assertGreaterThan(0, $hero->getDamage());
    }

    public function testRandomHeroHasFourSpells(): void
    {
        $hero = $this->heroFactory->createRandomHero('Hero');
        
        $this->assertCount(4, $hero->getSpells());
    }

    public function testRandomHeroAttributesAreWithinReasonableRange(): void
    {
        $hero = $this->heroFactory->createRandomHero('Test');
        
        // Strength, Intelligence, Speed, Damage should be between 1 and 200
        $this->assertGreaterThanOrEqual(1, $hero->getStrength());
        $this->assertLessThanOrEqual(200, $hero->getStrength());
        
        $this->assertGreaterThanOrEqual(1, $hero->getIntelligence());
        $this->assertLessThanOrEqual(200, $hero->getIntelligence());
        
        $this->assertGreaterThanOrEqual(1, $hero->getSpeed());
        $this->assertLessThanOrEqual(200, $hero->getSpeed());
        
        $this->assertGreaterThanOrEqual(1, $hero->getDamage());
        $this->assertLessThanOrEqual(200, $hero->getDamage());
        
        // Attack speed should be between 0.5 and 3.0
        $this->assertGreaterThanOrEqual(0.5, $hero->getAttackSpeed());
        $this->assertLessThanOrEqual(3.0, $hero->getAttackSpeed());
    }

    public function testDifferentHeroesHaveDifferentAttributes(): void
    {
        $hero1 = $this->heroFactory->createRandomHero('Hero1');
        $hero2 = $this->heroFactory->createRandomHero('Hero2');
        
        // It's possible but unlikely that all attributes are the same
        $attributes1 = [
            $hero1->getStrength(),
            $hero1->getIntelligence(),
            $hero1->getSpeed(),
            $hero1->getAttackSpeed(),
            $hero1->getDamage(),
        ];
        
        $attributes2 = [
            $hero2->getStrength(),
            $hero2->getIntelligence(),
            $hero2->getSpeed(),
            $hero2->getAttackSpeed(),
            $hero2->getDamage(),
        ];
        
        // At least one attribute should be different (very high probability)
        $this->assertNotEquals($attributes1, $attributes2);
    }
}

