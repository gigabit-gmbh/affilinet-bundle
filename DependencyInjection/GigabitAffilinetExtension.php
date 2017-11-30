<?php

namespace Gigabit\AffilinetBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class GigabitAffilinetExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $def = $container->getDefinition('affilinet.product_client');
        $def->replaceArgument(1, $config['publisher']['id']);
        $def->replaceArgument(2, $config['publisher']['product_password']);

        $def = $container->getDefinition('affilinet.publisher_client');
        $def->replaceArgument(1, $config['publisher']['id']);
        $def->replaceArgument(2, $config['publisher']['publisher_password']);
    }
}
