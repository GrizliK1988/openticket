<?php
/**
 * User: Dmitry Grachikov
 * Date: 08.11.15
 * Time: 22:29
 */

namespace DG\OpenticketBundle\Tests\Unit\DependencyInjection;


use DG\OpenticketBundle\DependencyInjection\DGOpenticketExtension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DGOpenticketExtensionTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class DGOpenticketExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadFail()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ContainerBuilder $containerMock */
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        try {
            $extension = new DGOpenticketExtension();
            $extension->load([
                ['doctrine_type' => 'XXX']
            ], $containerMock);
        } catch (InvalidConfigurationException $invalidConfigurationException) {
            $this->assertContains('The value "XXX" is not allowed for path "dg_openticket.doctrine_type"', $invalidConfigurationException->getMessage());
        }
    }

    public function testLoadSuccess()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ContainerBuilder $containerMock */
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerMock->expects($this->once())->method('setParameter')->with('db_openticket.doctrine_orm_enabled', true);

        $extension = new DGOpenticketExtension();
        $extension->load([
            ['doctrine_type' => 'orm']
        ], $containerMock);
    }
}
 