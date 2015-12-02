<?php
/**
 * User: Dmitry Grachikov
 * Date: 08.11.15
 * Time: 23:14
 */

namespace DG\OpenticketBundle\Tests\Unit;


use DG\OpenticketBundle\DGOpenticketBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DGOpenticketBundleTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class DGOpenticketBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBundleBuild()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ContainerBuilder $containerMock */
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerMock->expects($this->at(0))->method('addCompilerPass')
            ->with($this->isInstanceOf('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass'));

        $containerMock->expects($this->at(1))->method('addCompilerPass')
            ->with($this->isInstanceOf('DG\OpenticketBundle\DependencyInjection\Compiler\DbFixtureCompilerPass'));

        $bundle = new DGOpenticketBundle();
        $bundle->build($containerMock);
    }
}
 