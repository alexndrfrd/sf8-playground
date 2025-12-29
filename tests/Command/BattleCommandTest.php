<?php

namespace App\Tests\Command;

use App\Service\BattleService;
use App\Service\HeroFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Command\BattleCommand;

class BattleCommandTest extends TestCase
{
    private BattleService $battleService;
    private HeroFactory $heroFactory;

    protected function setUp(): void
    {
        $this->battleService = new BattleService();
        $this->heroFactory = new HeroFactory();
    }

    public function testCommandExists(): void
    {
        $command = new BattleCommand($this->battleService, $this->heroFactory);
        
        $this->assertInstanceOf(BattleCommand::class, $command);
    }

    public function testCommandHasCorrectName(): void
    {
        $command = new BattleCommand($this->battleService, $this->heroFactory);
        
        $this->assertEquals('battle:fight', $command->getName());
    }

    public function testCommandCanExecuteWithTwoHeroNames(): void
    {
        $command = new BattleCommand($this->battleService, $this->heroFactory);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'hero1' => 'Warrior',
            'hero2' => 'Mage',
        ]);

        $this->assertEquals(0, $commandTester->getStatusCode());
    }

    public function testCommandOutputContainsWinner(): void
    {
        $command = new BattleCommand($this->battleService, $this->heroFactory);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'hero1' => 'Warrior',
            'hero2' => 'Mage',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Winner', $output);
    }

    public function testCommandOutputContainsBattleRounds(): void
    {
        $command = new BattleCommand($this->battleService, $this->heroFactory);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'hero1' => 'Warrior',
            'hero2' => 'Mage',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Round', $output);
    }
}

