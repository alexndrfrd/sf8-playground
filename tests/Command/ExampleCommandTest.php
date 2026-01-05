<?php

namespace App\Tests\Command;

use App\Command\ExampleCommand;
use App\Service\ExampleService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ExampleCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $mockService = $this->createMock(ExampleService::class);
        $command = new ExampleCommand($mockService);

        $this->assertInstanceOf(ExampleCommand::class, $command);
    }

    public function testCommandCanExecute(): void
    {
        $mockService = $this->createMock(ExampleService::class);
        $mockService->expects($this->once())
            ->method('processData')
            ->with('test')
            ->willReturn('TEST (processed)');

        $command = new ExampleCommand($mockService);
        $commandTester = new CommandTester($command);
        $commandTester->execute(['data' => 'test']);

        $this->assertEquals(0, $commandTester->getStatusCode());
        $this->assertStringContainsString('Processed', $commandTester->getDisplay());
    }
}

