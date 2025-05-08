<?php

declare(strict_types=1);

namespace FooBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Example command that demonstrates a master command in the command chain.
 * This command is used as an example of a master command that can have member commands
 * executed after it.
 */
class FooHelloCommand extends Command
{
    /**
     * Configures the command with its name.
     */
    protected function configure(): void
    {
        $this->setName('foo:hello');
    }

    /**
     * Executes the command and outputs a greeting message.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln('Hello from Foo!');

        return Command::SUCCESS;
    }
}
