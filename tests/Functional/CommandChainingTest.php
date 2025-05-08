<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

class CommandChainingTest extends KernelTestCase
{
    public function testFooCommandChainsBar(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $tester = new ApplicationTester($application);
        $tester->run([
            'command' => 'foo:hello',
        ]);

        $this->assertStringContainsString('Hi from Bar!', $tester->getDisplay());
        $this->assertStringContainsString('Hello from Foo!', $tester->getDisplay());
    }

    public function testBarCannotRun(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $tester = new ApplicationTester($application);
        $tester->run([
            'command' => 'bar:hi',
        ]);

        $this->assertStringContainsString('bar:hi command is a member of foo:hello command chain and cannot be executed on its own.', $tester->getDisplay());
    }
}