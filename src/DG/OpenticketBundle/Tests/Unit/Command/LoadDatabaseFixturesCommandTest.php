<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Tests\Unit\Command;


use DG\OpenticketBundle\Command\LoadDatabaseFixturesCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class LoadDatabaseFixturesCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var LoadDatabaseFixturesCommand
     */
    private $command;

    /**
     * @var \DG\OpenticketBundle\DataFixtures\FixtureManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fixtureManagerMock;

    /**
     * @var \Symfony\Component\Console\Helper\QuestionHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $questionHelperMock;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->fixtureManagerMock = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureManagerInterface');

        $this->application = new Application();
        $this->questionHelperMock = $this->getMockBuilder('Symfony\Component\Console\Helper\QuestionHelper')->disableOriginalConstructor()->getMock();
        $helperSet = new HelperSet(['question' => $this->questionHelperMock]);
        $this->application->setHelperSet($helperSet);

        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcherMock */
        $eventDispatcherMock = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        /** @var \Symfony\Component\Translation\TranslatorInterface $translatorMock */
        $translatorMock = $this->getMockForAbstractClass('Symfony\Component\Translation\TranslatorInterface');

        $this->command = new LoadDatabaseFixturesCommand($this->fixtureManagerMock, $eventDispatcherMock, $translatorMock);
        $this->application->addCommands([$this->command]);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->fixtureManagerMock = null;

        $this->application = null;
        $this->command = null;
    }

    public function testExecuteWithoutInteraction()
    {
        /** @var \DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $fixtureLoaders */
        $fixtureLoaders = [];
        $this->fixtureManagerMock->expects($this->once())->method('getFixtureLoaders')->willReturn([
            $fixtureLoaders[] = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface'),
            $fixtureLoaders[] = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface'),
        ]);

        $fixtureLoaders[0]->expects($this->any())->method('getName')->willReturn('fixture 0');
        $fixtureLoaders[0]->expects($this->once())->method('load');
        $fixtureLoaders[1]->expects($this->any())->method('getName')->willReturn('fixture 1');
        $fixtureLoaders[1]->expects($this->once())->method('load');

        $command = $this->application->find('load:db:fixtures');
        $commandTester = new CommandTester($command);
        $commandTester->execute([], [
            'interactive' => false,
        ]);
    }

    public function testExecuteWithoutInteractionAndWithSomeLoaded()
    {
        /** @var \DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $fixtureLoaders */
        $fixtureLoaders = [];
        $this->fixtureManagerMock->expects($this->once())->method('getFixtureLoaders')->willReturn([
            $fixtureLoaders[] = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface'),
            $fixtureLoaders[] = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface'),
        ]);

        $fixtureLoaders[0]->expects($this->any())->method('getName')->willReturn('fixture 0');
        $fixtureLoaders[0]->expects($this->once())->method('load');
        $fixtureLoaders[1]->expects($this->any())->method('getName')->willReturn('fixture 1');
        $fixtureLoaders[1]->expects($this->once())->method('load');

        $command = $this->application->find('load:db:fixtures');
        $commandTester = new CommandTester($command);
        $commandTester->execute([], [
            'interactive' => false,
        ]);
    }

    public function testExecuteWithInteraction()
    {
        /** @var \DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $fixtureLoaders */
        $fixtureLoaders = [];
        $this->fixtureManagerMock->expects($this->exactly(2))->method('getFixtureLoaders')->willReturn([
            $fixtureLoaders[] = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface'),
            $fixtureLoaders[] = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface'),
        ]);

        $fixtureLoaders[0]->expects($this->any())->method('getName')->willReturn('fixture 0');
        $fixtureLoaders[0]->expects($this->once())->method('load');
        $fixtureLoaders[1]->expects($this->any())->method('getName')->willReturn('fixture 1');
        $fixtureLoaders[1]->expects($this->never())->method('load');

        $this->questionHelperMock->expects($this->once())->method('ask')->with($this->anything(), $this->anything(),
            $this->isInstanceOf('Symfony\Component\Console\Question\ChoiceQuestion'))->willReturn(['fixture 0']);

        $command = $this->application->find('load:db:fixtures');
        $commandTester = new CommandTester($command);
        $commandTester->execute([], [
            'interactive' => true,
        ]);
    }
}
