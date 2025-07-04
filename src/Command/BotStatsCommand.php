<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:bot:stats')]
class BotStatsCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = __DIR__ . '/../../var/bot_visits.log';
        $today = (new \DateTime())->format('Y-m-d');
        $count = 0;

        if (file_exists($file)) {
            foreach (file($file) as $line) {
                if (str_starts_with($line, $today)) {
                    $count++;
                }
            }
        }

        $output->writeln("Boty dzisiaj: $count");

        return Command::SUCCESS;
    }
}
