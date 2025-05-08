<?php

declare(strict_types=1);

namespace CommandChainBundle\Service;

/**
 * Registry service that maintains the relationships between master commands and their member commands.
 * This service is responsible for tracking which commands are chained together in the command chain system.
 */
class CommandChainRegistry
{
    private array $masterToMembers = [];
    private array $memberToMaster = [];

    /**
     * Registers a member command to be executed after a master command.
     */
    public function register(string $master, string $member): void
    {
        $this->masterToMembers[$master][] = $member;
        $this->memberToMaster[$member] = $master;
    }

    /**
     * Returns an array of member commands that are registered to be executed after the given master command.
     */
    public function getMembers(string $master): array
    {
        return $this->masterToMembers[$master] ?? [];
    }

    /**
     * Returns the master command that the given member command is registered to.
     */
    public function getMaster(string $member): ?string
    {
        return $this->memberToMaster[$member] ?? null;
    }
}