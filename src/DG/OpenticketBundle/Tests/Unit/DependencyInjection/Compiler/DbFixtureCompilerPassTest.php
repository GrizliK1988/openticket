<?php

namespace DG\OpenticketBundle\Tests\Unit\DependencyInjection\Compiler;


use DG\OpenticketBundle\DependencyInjection\Compiler\DbFixtureCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class DbFixtureCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $containerBuilderMock;

    /**
     * @var Definition|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fixtureManagerDefinitionMock;

    /**
     * @var DbFixtureCompilerPass
     */
    private $compilerPass;

    protected function setUp()
    {
        $this->containerBuilderMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->disableOriginalConstructor()->getMock();

        $this->fixtureManagerDefinitionMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')
            ->disableOriginalConstructor()->getMock();

        $this->compilerPass = new DbFixtureCompilerPass();
    }

    protected function tearDown()
    {
        $this->containerBuilderMock = null;
        $this->fixtureManagerDefinitionMock = null;
        $this->compilerPass = null;
    }

    public function testProcess()
    {
        $this->containerBuilderMock->expects($this->at(0))->method('findDefinition')
            ->with('dg_openticket.db_fixture.manager')->willReturn($this->fixtureManagerDefinitionMock);

        $this->containerBuilderMock->expects($this->at(1))->method('findTaggedServiceIds')
            ->with('dg_openticket.db_fixture')->willReturn([
                'fixture_id_1' => [],
                'fixture_id_2' => [['name' => 'dg_openticket.db_fixture']],
            ]);

        $this->containerBuilderMock->expects($this->at(2))->method('findDefinition')
            ->with('fixture_id_1')->willReturn($fixtureDef1 = new Definition());
        $this->fixtureManagerDefinitionMock->expects($this->at(0))->method('addMethodCall')->with('addFixtureLoader', [$fixtureDef1]);

        $this->containerBuilderMock->expects($this->at(3))->method('findDefinition')
            ->with('fixture_id_2')->willReturn($fixtureDef2 = new Definition());
        $this->fixtureManagerDefinitionMock->expects($this->at(1))->method('addMethodCall')->with('addFixtureLoader', [$fixtureDef2]);

        $this->compilerPass->process($this->containerBuilderMock);
    }
}
