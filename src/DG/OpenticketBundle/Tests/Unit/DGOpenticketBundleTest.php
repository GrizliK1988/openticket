<?php
/**
 * User: Dmitry Grachikov
 * Date: 08.11.15
 * Time: 23:14
 */

namespace DG\OpenticketBundle\Tests\Unit;


use DG\OpenticketBundle\DGOpenticketBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DGOpenticketBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBundleBuild()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ContainerBuilder $containerMock */
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerMock->expects($this->once())->method('addCompilerPass')
            ->with($this->isInstanceOf('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass'));

        $bundle = new DGOpenticketBundle();
        $bundle->build($containerMock);
    }
}
 