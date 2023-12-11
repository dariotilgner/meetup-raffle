<?php

declare(strict_types=1);

namespace Dariotilgner\MeetupRaffle\Command;

use Dariotilgner\MeetupRaffle\Command\Style\MeetupStyle;
use Dariotilgner\MeetupRaffle\Service\RaffleService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RaffleCommand extends Command
{
    public function __construct(private readonly RaffleService $raffleService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('meetup-raffle')
            ->addArgument('meetupAttendeesCsvFile', InputArgument::REQUIRED, 'Path to the meetup attendees CSV file')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('🚀 Meetup Raffle Script 🚀');
        $io = new MeetupStyle($input, $output);

        $file = $input->getArgument('meetupAttendeesCsvFile');

        $winner = $this->raffleService->raffle($file) ?? 'No winner found :(';
        $winner = 'Dario Tilgner';
        $winnersCount = $this->raffleService->getWinnersCount();

        $io->winnerBlock($winner, $winnersCount);

        return self::SUCCESS;
    }
}