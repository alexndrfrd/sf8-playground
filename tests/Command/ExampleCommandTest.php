<?php

namespace App\Tests\Command;

use App\Command\ExampleCommand;
use App\Service\ExampleHelperService;
use App\Service\ExampleService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ExampleCommandTest extends TestCase
{
    public function testCommandExists(): void
    {
        $helperService = new ExampleHelperService();
        $service = new ExampleService($helperService);
        $command = new ExampleCommand($service);

        $this->assertInstanceOf(ExampleCommand::class, $command);
    }

    public function testCommandCanExecute(): void
    {
        $helperService = new ExampleHelperService();
        $service = new ExampleService($helperService);
        
        $command = new ExampleCommand($service);
        $commandTester = new CommandTester($command);
        $commandTester->execute(['data' => 'test']);

        $this->assertEquals(0, $commandTester->getStatusCode());
        $this->assertStringContainsString('Processed', $commandTester->getDisplay());
    }

    public function testCommandWithMockedService(): void
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
        $this->assertStringContainsString('TEST (processed)', $commandTester->getDisplay());
    }
}

