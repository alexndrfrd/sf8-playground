<?php

namespace App\Command;

use App\Repository\CharacterRepository;
use App\Service\BattleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'battle:fight',
    description: 'Simulate a battle between two characters',
)]
class BattleCommand extends Command
{
    public function __construct(
        private readonly BattleService $battleService,
        private readonly CharacterRepository $characterRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('character1', InputArgument::REQUIRED, 'Name of the first character')
            ->addArgument('character2', InputArgument::REQUIRED, 'Name of the second character');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $character1Name = $input->getArgument('character1');
        $character2Name = $input->getArgument('character2');

        $character1 = $this->characterRepository->findByName($character1Name);
        $character2 = $this->characterRepository->findByName($character2Name);

        if (!$character1) {
            $io->error(sprintf('Character "%s" not found.', $character1Name));
            return Command::FAILURE;
        }

        if (!$character2) {
            $io->error(sprintf('Character "%s" not found.', $character2Name));
            return Command::FAILURE;
        }

        if ($character1Name === $character2Name) {
            $io->error('A character cannot battle itself.');
            return Command::FAILURE;
        }

        $io->title('⚔️  Battle Arena');
        $io->section(sprintf('%s vs %s', $character1->getName(), $character2->getName()));

        // Display character stats
        $io->table(
            ['Character', 'Strength', 'Intelligence', 'Health'],
            [
                [
                    $character1->getName(),
                    $character1->getStrength(),
                    $character1->getIntelligence(),
                    $character1->getHealth(),
                ],
                [
                    $character2->getName(),
                    $character2->getStrength(),
                    $character2->getIntelligence(),
                    $character2->getHealth(),
                ],
            ]
        );

        // Clone characters for battle
        $battleChar1 = new \App\Entity\Character(
            $character1->getName(),
            $character1->getStats(),
            $character1->getHealth()
        );
        $battleChar2 = new \App\Entity\Character(
            $character2->getName(),
            $character2->getStats(),
            $character2->getHealth()
        );

        $io->writeln('');
        $io->writeln('Battle starting...');
        $io->writeln('');

        $battleResult = $this->battleService->battle($battleChar1, $battleChar2);

        // Display battle history
        foreach ($battleResult['history'] as $turn) {
            $critical = $turn['isCritical'] ? ' 💥 CRITICAL!' : '';
            $io->writeln(sprintf(
                'Turn %d: %s attacks %s for %d damage%s (Health: %d)',
                $turn['turn'],
                $turn['attacker'],
                $turn['defender'],
                $turn['damage'],
                $critical,
                $turn['defenderHealth']
            ));
        }

        $io->writeln('');
        $io->success(sprintf(
            '🏆 Winner: %s (after %d turns)',
            $battleResult['winner']->getName(),
            $battleResult['turns']
        ));

        return Command::SUCCESS;
    }
}
