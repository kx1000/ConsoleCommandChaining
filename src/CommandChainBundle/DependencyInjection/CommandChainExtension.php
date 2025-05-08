<?php

declare(strict_types=1);

namespace CommandChainBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Extension class for loading the command chain bundle configuration and services.
 * This extension is responsible for loading the bundle's service definitions from YAML files.
 */
class CommandChainExtension extends Extension
{
    /**
     * Loads the bundle's service configuration from YAML files.
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}