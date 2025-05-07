<?php

declare(strict_types=1);

namespace CommandChainBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CommandChainCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('command_chain.registry')) {
            return;
        }

        $definition = $container->findDefinition('command_chain.registry');

        foreach ($container->findTaggedServiceIds('command_chain.member') as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('register', [$attributes['master'], $id]);
            }
        }
    }
}