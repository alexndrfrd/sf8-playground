<?php

namespace App\Tests\Entity;

use App\Entity\Character;
use App\ValueObject\CharacterStats;
use App\ValueObject\Race;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CharacterTest extends TestCase
{
    public function testCanCreateCharacter(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        $character = new Character('Warrior', $stats, $race);
        
        $this->assertInstanceOf(Character::class, $character);
    }

    public function testCharacterHasName(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        $character = new Character('Mage', $stats, $race);
        
        $this->assertEquals('Mage', $character->getName());
    }

    public function testCharacterHasStats(): void
    {
        $stats = new CharacterStats(20, 25);
        $race = Race::human();
        $character = new Character('Fighter', $stats, $race);
        
        $this->assertEquals(20, $character->getStats()->getStrength());
        $this->assertEquals(25, $character->getStats()->getIntelligence());
    }

    public function testCharacterHasRace(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::elf();
        $character = new Character('Warrior', $stats, $race);
        
        $this->assertEquals('Elf', $character->getRace()->getName());
    }

    public function testCharacterHasHealth(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        $character = new Character('Warrior', $stats, $race);
        
        $this->assertGreaterThan(0, $character->getHealth());
    }

    public function testCharacterHasDefaultHealth(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        $character = new Character('Warrior', $stats, $race);
        
        $this->assertEquals(100, $character->getHealth());
    }

    public function testCanSetCustomHealth(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        $character = new Character('Warrior', $stats, $race, 150);
        
        $this->assertEquals(150, $character->getHealth());
    }

    public function testNameCannotBeEmpty(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Character name cannot be empty');
        
        new Character('', $stats, $race);
    }

    public function testCanTakeDamage(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        $character = new Character('Warrior', $stats, $race, 100);
        
        $character->takeDamage(30);
        
        $this->assertEquals(70, $character->getHealth());
    }

    public function testHealthCannotGoBelowZero(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        $character = new Character('Warrior', $stats, $race, 50);
        
        $character->takeDamage(100);
        
        $this->assertEquals(0, $character->getHealth());
    }

    public function testIsAliveWhenHealthGreaterThanZero(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        $character = new Character('Warrior', $stats, $race, 50);
        
        $this->assertTrue($character->isAlive());
    }

    public function testIsDeadWhenHealthIsZero(): void
    {
        $stats = new CharacterStats(10, 15);
        $race = Race::human();
        $character = new Character('Warrior', $stats, $race, 50);
        
        $character->takeDamage(50);
        
        $this->assertFalse($character->isAlive());
    }
}
