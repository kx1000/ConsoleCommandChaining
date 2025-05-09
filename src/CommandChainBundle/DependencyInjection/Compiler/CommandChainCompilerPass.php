<?php

declare(strict_types=1);

namespace CommandChainBundle\DependencyInjection\Compiler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Compiler pass that processes command chain member services and registers them with the command chain registry.
 * This pass is responsible for building the command chain relationships during container compilation.
 */
class CommandChainCompilerPass implements CompilerPassInterface
{
    /**
     * Processes the container to register command chain members.
     * Looks for services tagged with 'command_chain.member' and registers them with the command chain registry.
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('command_chain.registry')) {
            return;
        }

        $definition = $container->findDefinition('command_chain.registry');

        foreach ($container->findTaggedServiceIds('command_chain.member') as $id => $tags) {
            foreach ($tags as $attributes) {
                $commandDefinition = $container->getDefinition($id);
                $commandClass = $commandDefinition->getClass();
                
                if (!is_subclass_of($commandClass, Command::class)) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must be a subclass of "%s".', $id, Command::class));
                }

                // Create a temporary instance to get the command name
                $command = new $commandClass();
                $commandName = $command->getName();
                
                $definition->addMethodCall('register', [$attributes['master'], $commandName]);
            }
        }
    }
}