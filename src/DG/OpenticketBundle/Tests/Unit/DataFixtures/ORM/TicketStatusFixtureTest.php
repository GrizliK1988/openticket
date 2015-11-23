<?php

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\ORM\TicketStatusFixture;
use DG\OpenticketBundle\Model\Ticket\Status;
use DG\OpenticketBundle\Model\Translation;
use Doctrine\ORM\EntityManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketStatusFixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $managerMock;

    /**
     * @var TicketStatusFixture
     */
    private $fixture;

    protected function setUp()
    {
        $this->managerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->fixture = new TicketStatusFixture($this->managerMock);
    }

    protected function tearDown()
    {
        $this->managerMock = null;
        $this->fixture = null;
    }

    public function testLoad()
    {
        $this->managerMock->expects($this->at(0))->method('persist')->with($this->callback(function (Status $status) {
            $this->assertFalse($status->isDeleted());
            return true;
        }))->willReturnCallback(function (Status $status) {
            $this->setStatusId($status, 1);
        });
        $this->managerMock->expects($this->at(1))->method('persist')->with($this->callback(function (Status $status) {
            $this->assertFalse($status->isDeleted());
            return true;
        }))->willReturnCallback(function (Status $status) {
            $this->setStatusId($status, 2);
        });
        $this->managerMock->expects($this->at(2))->method('persist')->with($this->callback(function (Status $status) {
            $this->assertFalse($status->isDeleted());
            return true;
        }))->willReturnCallback(function (Status $status) {
            $this->setStatusId($status, 3);
        });

        $this->managerMock->expects($this->at(3))->method('flush');

        $this->managerMock->expects($this->at(4))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_status', $translation->getDomain());
            $this->assertEquals('en', $translation->getLocale());
            $this->assertEquals(1, $translation->getKey());
            $this->assertEquals('Opened', $translation->getTranslation());
            return true;
        }));
        $this->managerMock->expects($this->at(5))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_status', $translation->getDomain());
            $this->assertEquals('ru', $translation->getLocale());
            $this->assertEquals(1, $translation->getKey());
            $this->assertEquals('Открыт', $translation->getTranslation());
            return true;
        }));

        $this->managerMock->expects($this->at(6))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_status', $translation->getDomain());
            $this->assertEquals('en', $translation->getLocale());
            $this->assertEquals(2, $translation->getKey());
            $this->assertEquals('In progress', $translation->getTranslation());
            return true;
        }));
        $this->managerMock->expects($this->at(7))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_status', $translation->getDomain());
            $this->assertEquals('ru', $translation->getLocale());
            $this->assertEquals(2, $translation->getKey());
            $this->assertEquals('Взят в работу', $translation->getTranslation());
            return true;
        }));

        $this->managerMock->expects($this->at(8))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_status', $translation->getDomain());
            $this->assertEquals('en', $translation->getLocale());
            $this->assertEquals(3, $translation->getKey());
            $this->assertEquals('Closed', $translation->getTranslation());
            return true;
        }));
        $this->managerMock->expects($this->at(9))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_status', $translation->getDomain());
            $this->assertEquals('ru', $translation->getLocale());
            $this->assertEquals(3, $translation->getKey());
            $this->assertEquals('Закрыт', $translation->getTranslation());
            return true;
        }));

        $this->managerMock->expects($this->at(10))->method('flush');

        $this->fixture->load();
    }

    private function setStatusId(Status $status, $id)
    {
        $statusReflected = new \ReflectionObject($status);
        $idProperty = $statusReflected->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($status, $id);
    }
}
