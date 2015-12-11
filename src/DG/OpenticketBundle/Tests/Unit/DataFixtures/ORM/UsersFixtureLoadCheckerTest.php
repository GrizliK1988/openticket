<?php

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureDataInterface;
use DG\OpenticketBundle\DataFixtures\ORM\UsersFixtureLoadChecker;
use DG\OpenticketBundle\Model\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UsersFixtureLoadCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UsersFixtureLoadChecker
     */
    private $fixtureLoadChecker;

    /**
     * @var EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $managerMock;

    /**
     * @var ObjectRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $userRepositoryMock;

    /**
     * @var FixtureDataInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fixtureDataMock;

    protected function setUp()
    {
        $this->fixtureDataMock = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureDataInterface');
        $this->managerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->userRepositoryMock = $this->getMockForAbstractClass('Doctrine\Common\Persistence\ObjectRepository');
        $this->fixtureLoadChecker = new UsersFixtureLoadChecker($this->fixtureDataMock, $this->managerMock);
    }

    protected function tearDown()
    {
        $this->fixtureLoadChecker = null;
        $this->userRepositoryMock = null;
        $this->managerMock = null;
    }

    public function testFixtureHasNotBeenLoaded_All()
    {
        $this->fixtureDataMock->expects($this->once())->method('getData')->willReturn([
            [
                'username' => 'dummy_user_1',
                'password' => 'dummy_password_1',
                'email' => 'dummy_email_1',
                'roles' => ['DUMMY_ROLE_1']
            ],
            [
                'username' => 'dummy_user_2',
                'password' => 'dummy_password_2',
                'email' => 'dummy_email_2',
                'roles' => ['DUMMY_ROLE_2']
            ],
        ]);

        $this->managerMock->expects($this->once())->method('getRepository')->with('DGOpenticketBundle:User')
            ->willReturn($this->userRepositoryMock);

        $this->userRepositoryMock->expects($this->exactly(2))->method('findOneBy')
            ->withConsecutive([['username' => 'dummy_user_1']], [['username' => 'dummy_user_2']])
            ->willReturn(null);

        $loaded = $this->fixtureLoadChecker->hasBeenLoaded();
        $this->assertFalse($loaded);
    }

    public function testFixtureBeenLoaded_Last()
    {
        $this->fixtureDataMock->expects($this->once())->method('getData')->willReturn([
            [
                'username' => 'dummy_user_1',
                'password' => 'dummy_password_1',
                'email' => 'dummy_email_1',
                'roles' => ['DUMMY_ROLE_1']
            ],
            [
                'username' => 'dummy_user_2',
                'password' => 'dummy_password_2',
                'email' => 'dummy_email_2',
                'roles' => ['DUMMY_ROLE_2']
            ],
        ]);

        $this->managerMock->expects($this->once())->method('getRepository')->with('DGOpenticketBundle:User')
            ->willReturn($this->userRepositoryMock);

        $this->userRepositoryMock->expects($this->exactly(2))->method('findOneBy')
            ->withConsecutive([['username' => 'dummy_user_1']], [['username' => 'dummy_user_2']])
            ->willReturnOnConsecutiveCalls(null, User::create());

        $loaded = $this->fixtureLoadChecker->hasBeenLoaded();
        $this->assertTrue($loaded);
    }

    public function testFixtureBeenLoaded_First()
    {
        $this->fixtureDataMock->expects($this->once())->method('getData')->willReturn([
            [
                'username' => 'dummy_user_1',
                'password' => 'dummy_password_1',
                'email' => 'dummy_email_1',
                'roles' => ['DUMMY_ROLE_1']
            ],
            [
                'username' => 'dummy_user_2',
                'password' => 'dummy_password_2',
                'email' => 'dummy_email_2',
                'roles' => ['DUMMY_ROLE_2']
            ],
        ]);

        $this->managerMock->expects($this->once())->method('getRepository')->with('DGOpenticketBundle:User')
            ->willReturn($this->userRepositoryMock);

        $this->userRepositoryMock->expects($this->exactly(1))->method('findOneBy')
            ->withConsecutive([['username' => 'dummy_user_1']])
            ->willReturnOnConsecutiveCalls(User::create());

        $loaded = $this->fixtureLoadChecker->hasBeenLoaded();
        $this->assertTrue($loaded);
    }
}
