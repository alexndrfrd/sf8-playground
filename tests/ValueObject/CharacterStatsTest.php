<?php

namespace App\Tests\ValueObject;

use App\ValueObject\CharacterStats;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CharacterStatsTest extends TestCase
{
    public function testCanCreateCharacterStats(): void
    {
        $stats = new CharacterStats(10, 15);
        
        $this->assertInstanceOf(CharacterStats::class, $stats);
    }

    public function testReturnsStrength(): void
    {
        $stats = new CharacterStats(20, 15);
        
        $this->assertEquals(20, $stats->getStrength());
    }

    public function testReturnsIntelligence(): void
    {
        $stats = new CharacterStats(10, 25);
        
        $this->assertEquals(25, $stats->getIntelligence());
    }

    public function testStrengthCannotBeNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Strength cannot be negative');
        
        new CharacterStats(-5, 10);
    }

    public function testIntelligenceCannotBeNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Intelligence cannot be negative');
        
        new CharacterStats(10, -5);
    }

    public function testCanHaveZeroValues(): void
    {
        $stats = new CharacterStats(0, 0);
        
        $this->assertEquals(0, $stats->getStrength());
        $this->assertEquals(0, $stats->getIntelligence());
    }

    public function testIsImmutable(): void
    {
        $stats1 = new CharacterStats(10, 15);
        $stats2 = new CharacterStats(10, 15);
        
        $this->assertEquals($stats1->getStrength(), $stats2->getStrength());
        $this->assertEquals($stats1->getIntelligence(), $stats2->getIntelligence());
    }

    public function testCanCalculateTotalPower(): void
    {
        $stats = new CharacterStats(10, 15);
        
        $this->assertEquals(25, $stats->getTotalPower());
    }
}

