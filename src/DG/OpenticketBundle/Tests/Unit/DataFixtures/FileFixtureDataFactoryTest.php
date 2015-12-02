<?php
/**
 * User: Dmitry Grachikov
 * Date: 08.12.15
 * Time: 20:36
 */

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures;


use DG\OpenticketBundle\DataFixtures\FileFixtureDataFactory;
use DG\OpenticketBundle\DataFixtures\FixtureData;

class FileFixtureDataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Config\FileLocatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fileLocatorMock;

    /**
     * @var FileFixtureDataFactory
     */
    private $fixtureDataFactory;

    protected function setUp()
    {
        $this->fileLocatorMock = $this->getMockForAbstractClass('Symfony\Component\Config\FileLocatorInterface');
        $this->fixtureDataFactory = new FileFixtureDataFactory($this->fileLocatorMock);
    }

    protected function tearDown()
    {
        $this->fileLocatorMock = null;
        $this->fixtureDataFactory = null;
    }

    public function testCreateFixtureData()
    {
        $this->fileLocatorMock->expects($this->once())->method('locate')->with('file_path')->willReturn(__DIR__ . '/../../Fixture/fixture.yml');

        $fixtureData = $this->fixtureDataFactory->createFixtureData('file_path');
        $this->assertEquals(new FixtureData(['test' => 'data']), $fixtureData);
    }
}
