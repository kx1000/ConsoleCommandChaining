<?php

declare(strict_types=1);

namespace App\CommandChainBundle\Tests\EventListener;

use CommandChainBundle\EventListener\CommandChainListener;
use CommandChainBundle\Service\CommandChainRegistry;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;

class CommandChainListenerTest extends TestCase
{
    public function testMemberCommandIsDisabled(): void
    {
        $registry = $this->createMock(CommandChainRegistry::class);
        $logger = $this->createMock(LoggerInterface::class);
        $command = $this->createMock(Command::class);
        $output = $this->createMock(OutputInterface::class);
        $application = $this->createMock(Application::class);
        $input = new ArrayInput([]);

        $registry->method('getMaster')->willReturn('master:command');
        $command->method('getName')->willReturn('member:command');
        $command->method('getApplication')->willReturn($application);

        $output->expects($this->once())
            ->method('writeln')
            ->with('<error>member:command command is a member of master:command command chain and cannot be executed on its own.</error>');

        $event = new ConsoleCommandEvent($command, $input, $output);

        $listener = new CommandChainListener($registry, $logger);
        $listener->onConsoleCommand($event);

        $this->assertFalse($event->commandShouldRun());
    }

    public function testMasterCommandWithNoMembersExecutesNormally(): void
    {
        $registry = $this->createMock(CommandChainRegistry::class);
        $logger = $this->createMock(LoggerInterface::class);
        $command = $this->createMock(Command::class);
        $output = $this->createMock(OutputInterface::class);
        $application = $this->createMock(Application::class);
        $input = new ArrayInput([]);

        $command->method('getName')->willReturn('master:command');
        $command->method('run')->willReturn(0);
        $command->method('getApplication')->willReturn($application);

        $registry->method('getMaster')->willReturn(null);
        $registry->method('getMembers')->willReturn([]);

        $event = new ConsoleCommandEvent($command, $input, $output);

        $listener = new CommandChainListener($registry, $logger);
        $listener->onConsoleCommand($event);

        $this->assertTrue($event->commandShouldRun());
    }

    public function testMasterCommandWithMembersExecutesAll(): void
    {
        $registry = $this->createMock(CommandChainRegistry::class);
        $logger = $this->createMock(LoggerInterface::class);
        $masterCommand = $this->createMock(Command::class);
        $memberCommand = $this->createMock(Command::class);
        $application = $this->createMock(Application::class);
        $output = $this->createMock(OutputInterface::class);
        $input = new ArrayInput([]);

        $masterCommand->method('getName')->willReturn('master:command');
        $masterCommand->method('run')->willReturnCallback(function ($input, $output) {
            $output->write('Master Output');
            return 0;
        });
        $masterCommand->method('getApplication')->willReturn($application);

        $memberCommand->method('run')->willReturnCallback(function ($input, $output) {
            $output->write('Member Output');
            return 0;
        });

        $application->method('find')->willReturn($memberCommand);

        $registry->method('getMaster')->willReturn(null);
        $registry->method('getMembers')->willReturn(['member:command']);

        $logger->expects($this->atLeastOnce())->method('info');

        $output->expects($this->once())->method('writeln')->with('Member Output');

        $event = new ConsoleCommandEvent($masterCommand, $input, $output);

        $listener = new CommandChainListener($registry, $logger);
        $listener->onConsoleCommand($event);

        $this->assertTrue($event->commandShouldRun());
    }
}