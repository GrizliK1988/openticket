<?php

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureDataInterface;
use DG\OpenticketBundle\DataFixtures\ORM\TicketRelationsFixtureLoadChecker;
use DG\OpenticketBundle\Model\Translation;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketRelationsFixtureLoadCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TicketRelationsFixtureLoadChecker
     */
    private $fixtureLoadChecker;

    /**
     * @var EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $managerMock;

    /**
     * @var ObjectRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $translationRepositoryMock;

    /**
     * @var FixtureDataInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fixtureDataMock;

    protected function setUp()
    {
        $this->fixtureDataMock = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureDataInterface');
        $this->managerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->translationRepositoryMock = $this->getMockForAbstractClass('Doctrine\Common\Persistence\ObjectRepository');
        $this->fixtureLoadChecker = new TicketRelationsFixtureLoadChecker($this->fixtureDataMock, $this->managerMock);
    }

    protected function tearDown()
    {
        $this->fixtureLoadChecker = null;
        $this->translationRepositoryMock = null;
        $this->managerMock = null;
    }

    public function testFixtureHasNotBeenLoaded_All()
    {
        $this->fixtureDataMock->expects($this->once())->method('getData')->willReturn([
            [
                ['locale' => 'en', 'translation' => 'Test'],
                ['locale' => 'ru', 'translation' => 'Тест'],
            ],
        ]);

        $this->managerMock->expects($this->once())->method('getRepository')->with('DGOpenticketBundle:Translation')
            ->willReturn($this->translationRepositoryMock);

        $this->translationRepositoryMock->expects($this->at(0))->method('findOneBy')
            ->with(['locale' => 'en', 'translation' => 'Test'])
            ->willReturn(null);
        $this->translationRepositoryMock->expects($this->at(1))->method('findOneBy')
            ->with(['locale' => 'ru', 'translation' => 'Тест'])
            ->willReturn(null);

        $loaded = $this->fixtureLoadChecker->hasBeenLoaded();
        $this->assertFalse($loaded);
    }

    public function testFixtureBeenLoaded_Last()
    {
        $this->fixtureDataMock->expects($this->once())->method('getData')->willReturn([
            [
                ['locale' => 'en', 'translation' => 'Test'],
                ['locale' => 'ru', 'translation' => 'Тест'],
            ],
        ]);

        $this->managerMock->expects($this->once())->method('getRepository')->with('DGOpenticketBundle:Translation')
            ->willReturn($this->translationRepositoryMock);

        $this->translationRepositoryMock->expects($this->exactly(2))->method('findOneBy')
            ->withConsecutive([['locale' => 'en', 'translation' => 'Test']], [['locale' => 'ru', 'translation' => 'Тест']])
            ->willReturnOnConsecutiveCalls(null, Translation::create());

        $loaded = $this->fixtureLoadChecker->hasBeenLoaded();
        $this->assertTrue($loaded);
    }

    public function testFixtureBeenLoaded_First()
    {
        $this->fixtureDataMock->expects($this->once())->method('getData')->willReturn([
            [
                ['locale' => 'en', 'translation' => 'Test'],
                ['locale' => 'ru', 'translation' => 'Тест'],
            ],
        ]);

        $this->managerMock->expects($this->once())->method('getRepository')->with('DGOpenticketBundle:Translation')
            ->willReturn($this->translationRepositoryMock);

        $this->translationRepositoryMock->expects($this->exactly(1))->method('findOneBy')
            ->withConsecutive([['locale' => 'en', 'translation' => 'Test']])
            ->willReturnOnConsecutiveCalls(Translation::create());

        $loaded = $this->fixtureLoadChecker->hasBeenLoaded();
        $this->assertTrue($loaded);
    }
}
