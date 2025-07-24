<?php

namespace App\GameDetails\UI;

use App\GameDetails\Application\Service\GameDetailsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


#[AsCommand(
    name: 'game-details:generate',
    description: 'Generate Game Details with AI',
)]
class GenerateGameDetailsCommand extends Command
{
    public function __construct(
        private readonly GameDetailsService $gameDetailsService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'game-id',
            InputArgument::REQUIRED
        );
        $this->addOption(
            'save',
            's',
            InputOption::VALUE_NONE,
            'Save game details after generation'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $gameDetails = $this->gameDetailsService->generate(
            (int)$input->getArgument('game-id'),
        );
        if (!$gameDetails) {
            $io->error('Failed to generate game details!');
            return Command::FAILURE;
        }

        $this->gameDetailsService->upsert($gameDetails);

        $io->success('Game details have been generated and saved!');

        return Command::SUCCESS;
    }
}