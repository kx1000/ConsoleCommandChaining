<?php

declare(strict_types=1);

namespace BarBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BarHiCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('bar:hi');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln('Hi from Bar!');

        return Command::SUCCESS;
    }
} 