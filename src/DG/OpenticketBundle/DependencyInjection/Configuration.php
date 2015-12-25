<?php

namespace DG\OpenticketBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dg_openticket');

        $rootNode
            ->children()
                ->arrayNode('locales')->defaultValue(['en', 'ru'])->prototype('scalar')->end()->end()
                ->scalarNode('database_tables_scheme')->defaultNull()->end()
                ->enumNode('doctrine_type')
                    ->isRequired()
                    ->values(['orm', 'mongodb', 'couchdb'])
                ->end()
            ->end();

        return $treeBuilder;
    }
}
