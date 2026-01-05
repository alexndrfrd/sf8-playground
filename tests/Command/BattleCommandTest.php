<?php

namespace App\Tests\Command;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\ValueObject\CharacterStats;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class BattleCommandTest extends KernelTestCase
{
    public function testCommandExists(): void
    {
        $kernel = self::bootKernel();
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        
        $this->assertTrue($application->has('battle:fight'));
    }

    public function testCommandRequiresTwoCharacterNames(): void
    {
        $kernel = self::bootKernel();
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $command = $application->find('battle:fight');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute([
            'character1' => 'Warrior',
            'character2' => 'Mage',
        ]);
        
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Battle', $output);
    }

    public function testCommandShowsBattleResult(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        
        // Create test characters
        $stats1 = new CharacterStats(20, 10);
        $stats2 = new CharacterStats(10, 20);
        $character1 = new Character('TestWarrior', $stats1);
        $character2 = new Character('TestMage', $stats2);
        
        $em = $container->get('doctrine')->getManager();
        $em->persist($character1);
        $em->persist($character2);
        $em->flush();
        
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $command = $application->find('battle:fight');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute([
            'character1' => 'TestWarrior',
            'character2' => 'TestMage',
        ]);
        
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Winner', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());
        
        // Cleanup
        $em->remove($character1);
        $em->remove($character2);
        $em->flush();
    }
}

