<?php

namespace DG\OpenticketBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 * 
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class DGOpenticketExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $doctrineTypeParameterName = sprintf('db_openticket.doctrine_%s_enabled', $config['doctrine_type']);
        $container->setParameter($doctrineTypeParameterName, true);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->registerDatabaseTableScheme($config, $container);
    }

    private function registerDatabaseTableScheme(array $config,  ContainerBuilder $container)
    {
        if ($container->hasDefinition('dg_openticket.table_prefix_subscriber')) {
            $tablePrefixEventSubscriber = $container->findDefinition('dg_openticket.table_prefix_subscriber');
            $tablePrefixEventSubscriber->replaceArgument(0, $config['database_tables_scheme']);
        }
    }
}
