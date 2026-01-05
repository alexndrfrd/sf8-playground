<?php

namespace App\Tests\ValueObject;

use App\ValueObject\Race;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RaceTest extends TestCase
{
    public function testCanCreateRace(): void
    {
        $race = Race::from(\App\ValueObject\RaceName::HUMAN);
        
        $this->assertInstanceOf(Race::class, $race);
    }

    public function testReturnsName(): void
    {
        $race = Race::from(\App\ValueObject\RaceName::ELF);
        
        $this->assertEquals('Elf', $race->getName());
    }

    public function testReturnsCriticalHitChance(): void
    {
        $race = Race::from(\App\ValueObject\RaceName::ORC);
        
        $this->assertEquals(0.08, $race->getCriticalHitChance());
    }

    public function testReturnsCriticalHitMultiplier(): void
    {
        $race = Race::from(\App\ValueObject\RaceName::DWARF);
        
        $this->assertEquals(1.7, $race->getCriticalHitMultiplier());
    }

    public function testCriticalHitChanceCannotBeNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Critical hit chance cannot be negative');
        
        new Race(\App\ValueObject\RaceName::HUMAN, -0.1, 1.5);
    }

    public function testCriticalHitChanceCannotExceedOne(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Critical hit chance cannot exceed 1.0');
        
        new Race(\App\ValueObject\RaceName::HUMAN, 1.5, 1.5);
    }

    public function testCriticalHitMultiplierCannotBeLessThanOne(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Critical hit multiplier must be at least 1.0');
        
        new Race(\App\ValueObject\RaceName::HUMAN, 0.1, 0.5);
    }

    public function testIsImmutable(): void
    {
        $race1 = Race::from(\App\ValueObject\RaceName::HUMAN);
        $race2 = Race::from(\App\ValueObject\RaceName::HUMAN);
        
        $this->assertEquals($race1->getName(), $race2->getName());
        $this->assertEquals($race1->getCriticalHitChance(), $race2->getCriticalHitChance());
        $this->assertEquals($race1->getCriticalHitMultiplier(), $race2->getCriticalHitMultiplier());
    }

    public function testCanCreateRaceFromEnum(): void
    {
        $human = Race::from(\App\ValueObject\RaceName::HUMAN);
        $elf = Race::from(\App\ValueObject\RaceName::ELF);
        $orc = Race::from(\App\ValueObject\RaceName::ORC);
        $dwarf = Race::from(\App\ValueObject\RaceName::DWARF);
        
        $this->assertEquals('Human', $human->getName());
        $this->assertEquals('Elf', $elf->getName());
        $this->assertEquals('Orc', $orc->getName());
        $this->assertEquals('Dwarf', $dwarf->getName());
    }

    public function testCanCreateRaceFromString(): void
    {
        $human = Race::fromString('human');
        $elf = Race::fromString('elf');
        
        $this->assertEquals('Human', $human->getName());
        $this->assertEquals('Elf', $elf->getName());
    }

    public function testFromStringThrowsExceptionForInvalidRace(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid race name: invalid');
        
        Race::fromString('invalid');
    }

    public function testReturnsRaceNameEnum(): void
    {
        $race = Race::from(\App\ValueObject\RaceName::HUMAN);
        
        $this->assertEquals(\App\ValueObject\RaceName::HUMAN, $race->getRaceName());
    }
}

