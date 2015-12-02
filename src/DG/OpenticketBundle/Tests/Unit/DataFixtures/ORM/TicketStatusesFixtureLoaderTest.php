<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureDataInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoadCheckerInterface;
use DG\OpenticketBundle\DataFixtures\ORM\TicketStatusesFixtureLoader;
use DG\OpenticketBundle\Model\Ticket\Status;
use DG\OpenticketBundle\Model\Translation;
use Doctrine\ORM\EntityManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketStatusesFixtureLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TicketStatusesFixtureLoader
     */
    private $fixture;

    /**
     * @var EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $managerMock;

    /**
     * @var FixtureDataInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fixtureDataMock;

    /**
     * @var FixtureLoadCheckerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fixtureLoadCheckerMock;

    protected function setUp()
    {
        $this->fixtureLoadCheckerMock = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureLoadCheckerInterface');
        $this->fixtureDataMock = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureDataInterface');
        $this->managerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->fixture = new TicketStatusesFixtureLoader($this->fixtureDataMock, $this->fixtureLoadCheckerMock, $this->managerMock);
    }

    protected function tearDown()
    {
        $this->fixtureLoadCheckerMock = null;
        $this->fixtureDataMock = null;
        $this->fixture = null;
        $this->managerMock = null;
    }

    public function testLoadNoDuplicate()
    {
        $this->fixtureDataMock->expects($this->once())->method('getData')->willReturn([
            [
                ['locale' => 'en', 'translation' => 'Test1'],
                ['locale' => 'ru', 'translation' => 'Тест1'],
            ],
        ]);

        $this->fixtureLoadCheckerMock->expects($this->once())->method('hasBeenLoaded')->willReturn(false);

        $this->managerMock->expects($this->at(0))->method('persist')->willReturnCallback(function (Status $status) {
            $this->setStatusId($status, 1);
        });
        $this->managerMock->expects($this->at(1))->method('flush');
        $this->managerMock->expects($this->at(2))->method('persist')->with(Translation::create()->setLocale('en')->setTranslation('Test1')->setDomain('ticket_status')->setKey(1));
        $this->managerMock->expects($this->at(3))->method('persist')->with(Translation::create()->setLocale('ru')->setTranslation('Тест1')->setDomain('ticket_status')->setKey(1));
        $this->managerMock->expects($this->at(4))->method('flush');

        $this->fixture->load();
    }

    /**
     * @expectedException \DG\OpenticketBundle\Exception\DuplicateException
     */
    public function testLoadDuplicate()
    {
        $this->fixtureDataMock->expects($this->never())->method('getData');

        $this->fixtureLoadCheckerMock->expects($this->once())->method('hasBeenLoaded')->willReturn(true);

        $this->managerMock->expects($this->never())->method('persist');
        $this->managerMock->expects($this->never())->method('flush');

        $this->fixture->load();
    }

    private function setStatusId(Status $status, \int $id)
    {
        $statusReflected = new \ReflectionObject($status);
        $idProperty = $statusReflected->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($status, $id);
    }
}
