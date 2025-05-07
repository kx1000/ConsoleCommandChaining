<?php

declare(strict_types=1);

namespace CommandChainBundle\EventListener;

use CommandChainBundle\Service\CommandChainRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class CommandChainListener
{
    public function __construct(
        private readonly CommandChainRegistry $registry,
        private readonly LoggerInterface $logger
    ) {}

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $commandName = $event->getCommand()->getName();

        // Check if it is a member
        $master = $this->registry->getMaster($commandName);
        if ($master !== null) {
            $event->disableCommand();
            $event->getOutput()->writeln("<error>{$commandName} command is a member of {$master} command chain and cannot be executed on its own.</error>");
            return;
        }

        $members = $this->registry->getMembers($commandName);
        if (empty($members)) return;

        $this->logger->info("{$commandName} is a master command of a command chain that has registered member commands");
        foreach ($members as $member) {
            $this->logger->info("{$member} registered as a member of {$commandName} command chain");
        }

        $application = $event->getCommand()->getApplication();
        $output = $event->getOutput();

        $this->logger->info("Executing {$commandName} command itself first:");

        // command already running

        $this->logger->info("Executing {$commandName} chain members:");
        foreach ($members as $member) {
            $command = $application->find($member);
            $input = new \Symfony\Component\Console\Input\ArrayInput([]);
            $command->run($input, $output);
        }

        $this->logger->info("Execution of {$commandName} chain completed.");
    }
}