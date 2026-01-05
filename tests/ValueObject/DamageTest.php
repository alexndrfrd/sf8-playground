<?php

namespace App\Tests\ValueObject;

use App\ValueObject\Damage;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DamageTest extends TestCase
{
    public function testCanCreateDamage(): void
    {
        $damage = new Damage(100);
        
        $this->assertInstanceOf(Damage::class, $damage);
    }

    public function testDamageReturnsValue(): void
    {
        $damage = new Damage(150);
        
        $this->assertEquals(150, $damage->getValue());
    }

    public function testDamageCannotBeNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Damage cannot be negative');
        
        new Damage(-10);
    }

    public function testDamageCanBeZero(): void
    {
        $damage = new Damage(0);
        
        $this->assertEquals(0, $damage->getValue());
    }

    public function testDamageIsImmutable(): void
    {
        $damage1 = new Damage(100);
        $damage2 = new Damage(100);
        
        // Value Objects should be equal if values are equal
        $this->assertEquals($damage1->getValue(), $damage2->getValue());
    }

    public function testCanAddDamage(): void
    {
        $damage1 = new Damage(50);
        $damage2 = new Damage(30);
        
        $result = $damage1->add($damage2);
        
        $this->assertEquals(80, $result->getValue());
        // Original should be unchanged (immutability)
        $this->assertEquals(50, $damage1->getValue());
    }

    public function testCanSubtractDamage(): void
    {
        $damage1 = new Damage(100);
        $damage2 = new Damage(30);
        
        $result = $damage1->subtract($damage2);
        
        $this->assertEquals(70, $result->getValue());
    }

    public function testSubtractCannotResultInNegative(): void
    {
        $damage1 = new Damage(30);
        $damage2 = new Damage(100);
        
        $result = $damage1->subtract($damage2);
        
        // Should return 0, not negative
        $this->assertEquals(0, $result->getValue());
    }

    public function testCanMultiplyDamage(): void
    {
        $damage = new Damage(50);
        
        $result = $damage->multiply(1.5);
        
        $this->assertEquals(75, $result->getValue());
    }

    public function testCanCompareDamage(): void
    {
        $damage1 = new Damage(100);
        $damage2 = new Damage(50);
        $damage3 = new Damage(100);
        
        $this->assertTrue($damage1->isGreaterThan($damage2));
        $this->assertFalse($damage2->isGreaterThan($damage1));
        $this->assertTrue($damage1->equals($damage3));
        $this->assertFalse($damage1->equals($damage2));
    }

    public function testCanConvertToString(): void
    {
        $damage = new Damage(150);
        
        $this->assertEquals('150', (string)$damage);
        $this->assertEquals('150', $damage->toString());
    }
}

