<?php

declare(strict_types=1);

namespace Dariotilgner\MeetupRaffle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RaffleCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('meetup-raffle');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Hello World!');
        return self::SUCCESS;
    }
}