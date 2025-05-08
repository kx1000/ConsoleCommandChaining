<?php

declare(strict_types=1);

namespace CommandChainBundle;

use CommandChainBundle\DependencyInjection\Compiler\CommandChainCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Bundle that provides command chaining functionality for Symfony console commands.
 * This bundle allows commands to be chained together, where member commands are automatically
 * executed after their master command.
 */
class CommandChainBundle extends Bundle
{
    /**
     * Builds the bundle by adding the command chain compiler pass to the container.
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new CommandChainCompilerPass());
    }
}