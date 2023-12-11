<?php

declare(strict_types=1);

namespace Dariotilgner\MeetupRaffle\Service;

use InvalidArgumentException;

class RaffleService
{
    public function raffle(string $meetupAttendeesCsvFile): ?string
    {
        $ignoreUsersFile = __DIR__ . '/../../ignoreusers';
        $winnersFile = __DIR__ . '/../../winners';
        $this->createFileIfNotExists($ignoreUsersFile);
        $this->createFileIfNotExists($winnersFile);

        if (!file_exists($meetupAttendeesCsvFile)) {
            throw new InvalidArgumentException('Meetup attendees CSV file does not exist');
        }

        $participants = $this->getParticipantsFromFile($meetupAttendeesCsvFile);
        $ignoreUsers = $this->getParticipantsFromFile($ignoreUsersFile);
        $winners = $this->getParticipantsFromFile($winnersFile);

        $finalParticipants = array_diff($participants, $ignoreUsers, $winners);

        if (count($finalParticipants) === 0) {
            return null;
        }

        $winner = $finalParticipants[array_rand($finalParticipants)] ?? null;

        file_put_contents($winnersFile, $winner . PHP_EOL, FILE_APPEND);

        return $winner;
    }

    public function getWinnersCount(): int
    {
        $winnersFile = __DIR__ . '/../../winners';
        $this->createFileIfNotExists($winnersFile);

        $winners = $this->getParticipantsFromFile($winnersFile);

        return count($winners);
    }

    private function getParticipantsFromFile(string $meetupAttendeesCsvFile): array
    {
        $participants = [];

        $file = fopen($meetupAttendeesCsvFile, 'rb');

        while (($line = fgetcsv($file, separator: "\t")) !== false) {
            if ($line[0] === 'Name') {
                continue;
            }
            $participants[] = $line[0];
        }

        fclose($file);

        return array_filter($participants);
    }

    private function createFileIfNotExists(string $fileName): void
    {
        if (!file_exists($fileName)) {
            touch($fileName);
        }
    }
}