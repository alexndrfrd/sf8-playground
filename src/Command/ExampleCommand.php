<?php

namespace App\Command;

use App\Service\ExampleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'example:process',
    description: 'Example command that processes data',
)]
class ExampleCommand extends Command
{
    public function __construct(
        private readonly ExampleService $exampleService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('data', InputArgument::REQUIRED, 'Data to process');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $data = $input->getArgument('data');

        $io->title('Example Command');
        $io->section('Processing data...');

        $result = $this->exampleService->processData($data);

        $io->success(sprintf('Processed: %s -> %s', $data, $result));

        return Command::SUCCESS;
    }
}

