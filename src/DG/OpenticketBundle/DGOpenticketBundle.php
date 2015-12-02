<?php

namespace DG\OpenticketBundle;

use DG\OpenticketBundle\DependencyInjection\Compiler\DbFixtureCompilerPass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * DGOpenticketBundle
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class DGOpenticketBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $ormCompilerPass = 'Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass';
        if (class_exists($ormCompilerPass)) {
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createAnnotationMappingDriver([
                    'DG\OpenticketBundle\Model'
                ], [
                    __DIR__ . '/Model'
                ], [
                    'db_openticket.model_manager_name'
                ], 'db_openticket.doctrine_orm_enabled', [
                    'DGOpenticketBundle' => 'DG\OpenticketBundle\Model'
                ])
            );
        }

        $container->addCompilerPass(new DbFixtureCompilerPass());
    }
}
