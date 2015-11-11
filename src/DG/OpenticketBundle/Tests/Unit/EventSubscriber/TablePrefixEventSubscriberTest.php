<?php

namespace DG\OpenticketBundle\Tests\Unit\EventSubscriber;
use DG\OpenticketBundle\EventSubscriber\TablePrefixEventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TablePrefixEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TablePrefixEventSubscriber
     */
    private $tablePrefixAdder = null;

    protected function setUp()
    {
        $this->tablePrefixAdder = new TablePrefixEventSubscriber('test_scheme');
    }

    protected function tearDown()
    {
        $this->tablePrefixAdder = null;
    }

    public function testLoadClassMetadata()
    {
        /** @var LoadClassMetadataEventArgs|\PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMockBuilder('Doctrine\ORM\Event\LoadClassMetadataEventArgs')->disableOriginalConstructor()
            ->getMock();

        $classMetadataMock = $this->getMockBuilder('Doctrine\Common\Persistence\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->setMethods(['getTableName', 'setPrimaryTable'])->getMockForAbstractClass();

        $classMetadataMock->expects($this->once())->method('getTableName')->willReturn('table_name');
        $classMetadataMock->expects($this->once())->method('setPrimaryTable')->with($this->equalTo(['name' => 'test_scheme.table_name']));

        $eventMock->expects($this->once())->method('getClassMetadata')->willReturn($classMetadataMock);

        $this->tablePrefixAdder->loadClassMetadata($eventMock);
    }
}
 