<?php

declare(strict_types=1);

namespace CommandChainBundle;

use CommandChainBundle\DependencyInjection\Compiler\CommandChainCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CommandChainBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new CommandChainCompilerPass());
    }
}