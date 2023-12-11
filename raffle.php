<?php

declare(strict_types=1);

use Dariotilgner\MeetupRaffle\Command\RaffleCommand;
use Dariotilgner\MeetupRaffle\Service\RaffleService;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$application = new Application('meetup-raffle', '1.0.0');

$raffleCommand = new RaffleCommand(new RaffleService());

$application->add($raffleCommand);

$application->setDefaultCommand($raffleCommand->getName(), true);
$application->run();