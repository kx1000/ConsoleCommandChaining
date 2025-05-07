<?php

declare(strict_types=1);

namespace CommandChainBundle\Service;

class CommandChainRegistry
{
    private array $masterToMembers = [];
    private array $memberToMaster = [];

    public function register(string $master, string $member): void
    {
        $this->masterToMembers[$master][] = $member;
        $this->memberToMaster[$member] = $master;
    }

    public function getMembers(string $master): array
    {
        return $this->masterToMembers[$master] ?? [];
    }

    public function getMaster(string $member): ?string
    {
        return $this->memberToMaster[$member] ?? null;
    }
}