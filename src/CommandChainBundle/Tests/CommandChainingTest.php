<?php

declare(strict_types=1);

namespace App\CommandChainBundle\Tests;

use CommandChainBundle\Service\CommandChainRegistry;
use PHPUnit\Framework\TestCase;

class CommandChainingTest extends TestCase
{
    public function testRegisterAndGetMembers(): void
    {
        $registry = new CommandChainRegistry();
        $registry->register('foo:hello', 'bar:hi');

        $this->assertSame(['bar:hi'], $registry->getMembers('foo:hello'));
        $this->assertSame('foo:hello', $registry->getMaster('bar:hi'));
    }

    public function testMemberNotInChainReturnsNull(): void
    {
        $registry = new CommandChainRegistry();
        $this->assertNull($registry->getMaster('bar:hi'));
    }
}