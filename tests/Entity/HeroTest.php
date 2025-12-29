<?php

namespace App\Tests\Entity;

use App\Entity\Hero;
use PHPUnit\Framework\TestCase;

class HeroTest extends TestCase
{
    public function testHeroCanBeCreated(): void
    {
        $hero = new Hero();
        
        $this->assertInstanceOf(Hero::class, $hero);
    }

    public function testHeroHasName(): void
    {
        $hero = new Hero();
        $hero->setName('Test Hero');
        
        $this->assertEquals('Test Hero', $hero->getName());
    }

    public function testHeroHasStrength(): void
    {
        $hero = new Hero();
        $hero->setStrength(100);
        
        $this->assertEquals(100, $hero->getStrength());
    }

    public function testHeroHasIntelligence(): void
    {
        $hero = new Hero();
        $hero->setIntelligence(80);
        
        $this->assertEquals(80, $hero->getIntelligence());
    }

    public function testHeroHasSpeed(): void
    {
        $hero = new Hero();
        $hero->setSpeed(120);
        
        $this->assertEquals(120, $hero->getSpeed());
    }

    public function testHeroHasAttackSpeed(): void
    {
        $hero = new Hero();
        $hero->setAttackSpeed(1.5);
        
        $this->assertEquals(1.5, $hero->getAttackSpeed());
    }

    public function testHeroHasDamage(): void
    {
        $hero = new Hero();
        $hero->setDamage(50);
        
        $this->assertEquals(50, $hero->getDamage());
    }

    public function testHeroAttributesAreSetCorrectly(): void
    {
        $hero = new Hero();
        $hero->setName('Warrior');
        $hero->setStrength(150);
        $hero->setIntelligence(60);
        $hero->setSpeed(100);
        $hero->setAttackSpeed(1.2);
        $hero->setDamage(75);
        
        $this->assertEquals('Warrior', $hero->getName());
        $this->assertEquals(150, $hero->getStrength());
        $this->assertEquals(60, $hero->getIntelligence());
        $this->assertEquals(100, $hero->getSpeed());
        $this->assertEquals(1.2, $hero->getAttackSpeed());
        $this->assertEquals(75, $hero->getDamage());
    }
}

