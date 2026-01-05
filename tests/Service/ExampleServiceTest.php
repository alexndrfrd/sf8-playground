<?php

namespace App\Tests\Service;

use App\Service\ExampleService;
use App\ValueObject\Damage;
use PHPUnit\Framework\TestCase;

class ExampleServiceTest extends TestCase
{
    public function testCanProcessData(): void
    {
        $service = new ExampleService();

        $result = $service->processData('test');

        $this->assertEquals('TEST (processed)', $result);
    }

    public function testCanCalculateDamage(): void
    {
        $service = new ExampleService();

        $result = $service->calculateDamage(20);

        $this->assertInstanceOf(Damage::class, $result);
        $this->assertEquals(20, $result->getValue());
    }
}

