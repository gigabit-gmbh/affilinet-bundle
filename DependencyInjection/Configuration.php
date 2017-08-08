<?php

namespace Gigabit\AffilinetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface {
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gigabit_affilinet');

        $rootNode
            ->children()
                ->arrayNode('publisher')
                    ->isRequired()
                    ->children()
                        ->scalarNode('id')->isRequired()->end()
                        ->scalarNode('publisher_password')->isRequired()->end()
                        ->scalarNode('product_password')->isRequired()->end()
                    ->end()
                ->end()// bucket
            ->end();

        return $treeBuilder;
    }
}
