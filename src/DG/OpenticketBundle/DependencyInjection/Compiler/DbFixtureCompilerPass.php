<?php

namespace DG\OpenticketBundle\DependencyInjection\Compiler;


use DG\OpenticketBundle\DataFixtures\FixtureManagerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class DbFixtureCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $fixtureManagerService = $container->findDefinition(FixtureManagerInterface::DIC_NAME);
        $fixtureServiceIds = $container->findTaggedServiceIds('dg_openticket.db_fixture');

        foreach ($fixtureServiceIds as $serviceId => $tags) {
            $fixtureService = $container->findDefinition($serviceId);
            $fixtureManagerService->addMethodCall('addFixtureLoader', [$fixtureService]);
        }
    }
}