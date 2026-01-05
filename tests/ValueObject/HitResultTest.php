<?php

namespace App\Tests\ValueObject;

use App\ValueObject\Damage;
use App\ValueObject\HitResult;
use PHPUnit\Framework\TestCase;

class HitResultTest extends TestCase
{
    public function testCanCreateHitResult(): void
    {
        $damage = new Damage(50);
        $hitResult = new HitResult($damage, false);
        
        $this->assertInstanceOf(HitResult::class, $hitResult);
    }

    public function testReturnsDamage(): void
    {
        $damage = new Damage(75);
        $hitResult = new HitResult($damage, false);
        
        $this->assertEquals(75, $hitResult->getDamage()->getValue());
    }

    public function testReturnsIsCritical(): void
    {
        $damage = new Damage(50);
        $hitResult = new HitResult($damage, true);
        
        $this->assertTrue($hitResult->isCritical());
    }

    public function testCanCreateNonCriticalHit(): void
    {
        $damage = new Damage(30);
        $hitResult = new HitResult($damage, false);
        
        $this->assertFalse($hitResult->isCritical());
    }

    public function testIsImmutable(): void
    {
        $damage1 = new Damage(50);
        $damage2 = new Damage(50);
        $hitResult1 = new HitResult($damage1, true);
        $hitResult2 = new HitResult($damage2, true);
        
        $this->assertEquals($hitResult1->getDamage()->getValue(), $hitResult2->getDamage()->getValue());
        $this->assertEquals($hitResult1->isCritical(), $hitResult2->isCritical());
    }
}

