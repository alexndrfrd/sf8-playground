<?php

namespace App\Command;

use App\Service\BattleService;
use App\Service\HeroFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'battle:fight',
    description: 'Start a battle between two heroes',
)]
class BattleCommand extends Command
{
    public function __construct(
        private readonly BattleService $battleService,
        private readonly HeroFactory $heroFactory
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('hero1', InputArgument::REQUIRED, 'Name of the first hero')
            ->addArgument('hero2', InputArgument::REQUIRED, 'Name of the second hero');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $hero1Name = $input->getArgument('hero1');
        $hero2Name = $input->getArgument('hero2');

        $io->title('⚔️  Battle Arena ⚔️');
        $io->section(sprintf('Creating heroes: %s vs %s', $hero1Name, $hero2Name));

        // Create heroes
        $hero1 = $this->heroFactory->createRandomHero($hero1Name);
        $hero2 = $this->heroFactory->createRandomHero($hero2Name);

        // Display hero stats
        $io->section('Hero Stats');
        $io->table(
            ['Attribute', $hero1->getName(), $hero2->getName()],
            [
                ['Strength', $hero1->getStrength(), $hero2->getStrength()],
                ['Intelligence', $hero1->getIntelligence(), $hero2->getIntelligence()],
                ['Speed', $hero1->getSpeed(), $hero2->getSpeed()],
                ['Attack Speed', $hero1->getAttackSpeed(), $hero2->getAttackSpeed()],
                ['Damage', $hero1->getDamage(), $hero2->getDamage()],
                ['Health', $hero1->getMaxHealth(), $hero2->getMaxHealth()],
            ]
        );

        // Start battle
        $io->section('Battle Begins!');
        $result = $this->battleService->battle($hero1, $hero2);

        // Display rounds
        foreach ($result['rounds'] as $round) {
            $io->writeln(sprintf(
                'Round %d: %s attacks %s for %d damage. %s health: %d',
                $round['round'],
                $round['attacker'],
                $round['defender'],
                $round['damage'],
                $round['defender'],
                $round['defenderHealth']
            ));
        }

        // Display result
        $io->newLine();
        $io->success(sprintf(
            '🏆 Winner: %s (Health: %d)',
            $result['winner']->getName(),
            $result['winner']->getHealth()
        ));
        $io->error(sprintf(
            '💀 Loser: %s',
            $result['loser']->getName()
        ));
        $io->note(sprintf('Total rounds: %d', $result['totalRounds']));

        return Command::SUCCESS;
    }
}

