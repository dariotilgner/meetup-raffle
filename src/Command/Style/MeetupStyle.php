<?php

namespace Dariotilgner\MeetupRaffle\Command\Style;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\OutputWrapper;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\TrimmedBufferOutput;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;

class MeetupStyle extends SymfonyStyle
{
    public function winnerBlock(string $winnerMessage, int $winnersCount): void
    {
        $messages = [
            '🏆 🏆 🏆 🏆 🏆 🏆 🏆 WINNER 🏆 🏆 🏆 🏆 🏆 🏆 🏆',
            'Winner:              ' . $winnerMessage,
            'Amount of winners:   ' . $winnersCount,
            '🏆 🏆 🏆 🏆 🏆 🏆 🏆 WINNER 🏆 🏆 🏆 🏆 🏆 🏆 🏆',
        ];
        $styles = [
            'fg=white;bg=default',
            'fg=white;bg=default',
            'fg=#ed1c40;bg=default;options=bold',
            'fg=#ed1c40;bg=default;options=bold',
            'fg=white;bg=default',
            'fg=white;bg=default',
        ];
        $this->writeln($this->createWinnerBlock($messages, styles: $styles));
        $this->newLine();
    }

    /**
     * @param iterable $messages
     * @param string|null $type
     * @param list<string>|null $styles
     * @param string $prefix
     * @param bool $padding
     * @param bool $escape
     * @return array
     */
    private function createWinnerBlock(iterable $messages, string $type = null, array $styles = null, string $prefix = ' ', bool $padding = false, bool $escape = false): array
    {
        $indentLength = 0;
        $prefixLength = Helper::width(Helper::removeDecoration($this->getFormatter(), $prefix));
        $lines = [];

        if (null !== $type) {
            $type = sprintf('[%s] ', $type);
            $indentLength = Helper::width($type);
            $lineIndentation = str_repeat(' ', $indentLength);
        }

        // wrap and add newlines for each element
        $outputWrapper = new OutputWrapper();
        foreach ($messages as $key => $message) {
            if ($escape) {
                $message = OutputFormatter::escape($message);
            }

            $lines = array_merge(
                $lines,
                explode(\PHP_EOL, $outputWrapper->wrap(
                    $message,
                    self::MAX_LINE_LENGTH - $prefixLength - $indentLength,
                    \PHP_EOL
                ))
            );

            if (\count($messages) > 1 && $key < \count($messages) - 1) {
                $lines[] = '';
            }
        }

        $firstLineIndex = 0;
        if ($padding && $this->isDecorated()) {
            $firstLineIndex = 1;
            array_unshift($lines, '');
            $lines[] = '';
        }

        foreach ($lines as $i => &$line) {
            if (null !== $type) {
                $line = $firstLineIndex === $i ? $type.$line : $lineIndentation.$line;
            }

            $line = $prefix.$line;
            $line .= str_repeat(' ', max(self::MAX_LINE_LENGTH - Helper::width(Helper::removeDecoration($this->getFormatter(), $line)), 0));

            $style = $styles[$i] ?? null;
            if ($style) {
                $line = sprintf('<%s>%s</>', $style, $line);
            }
        }

        return $lines;
    }
}
