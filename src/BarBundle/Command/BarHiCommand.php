<?php

declare(strict_types=1);

namespace BarBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Example command that demonstrates a member command in the command chain.
 * This command is used as an example of a member command that can be executed
 * after its master command.
 */
class BarHiCommand extends Command
{
    /**
     * Configures the command with its name and description.
     */
    protected function configure(): void
    {
        $this->setName('bar:hi');
    }

    /**
     * Executes the command and outputs a greeting message.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln('Hi from Bar!');

        return Command::SUCCESS;
    }
} 