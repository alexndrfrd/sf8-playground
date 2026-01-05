<?php

namespace App\Tests\Service;

use App\Service\ExampleHelperService;
use App\Service\ExampleService;
use App\ValueObject\Damage;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ExampleServiceTest extends TestCase
{
    public function testCanProcessData(): void
    {
        $helperService = new ExampleHelperService();
        $service = new ExampleService($helperService);

        $result = $service->processData('test');

        $this->assertEquals('TEST (processed)', $result);
    }

    public function testProcessDataUsesHelperService(): void
    {
        $helperService = new ExampleHelperService();
        $service = new ExampleService($helperService);

        // Test with spaces - helper should transform them
        $result = $service->processData('hello world');

        $this->assertEquals('HELLO_WORLD (processed)', $result);
    }

    public function testProcessDataValidatesWithHelperService(): void
    {
        $helperService = new ExampleHelperService();
        $service = new ExampleService($helperService);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Data cannot be empty');

        $service->processData('');
    }

    public function testProcessDataWithMockedHelperService(): void
    {
        // Create mock for helper service
        $mockHelper = $this->createMock(ExampleHelperService::class);
        
        // Configure mock
        $mockHelper->expects($this->once())
            ->method('validateData')
            ->with('test')
            ->willReturn(true);
        
        $mockHelper->expects($this->once())
            ->method('formatData')
            ->with('test')
            ->willReturn('test');
        
        $mockHelper->expects($this->once())
            ->method('transformData')
            ->with('test')
            ->willReturn('test');

        $service = new ExampleService($mockHelper);
        $result = $service->processData('test');

        $this->assertEquals('TEST (processed)', $result);
    }

    public function testCanCalculateDamage(): void
    {
        $helperService = new ExampleHelperService();
        $service = new ExampleService($helperService);

        $result = $service->calculateDamage(20);

        $this->assertInstanceOf(Damage::class, $result);
        $this->assertEquals(20, $result->getValue());
    }
}

