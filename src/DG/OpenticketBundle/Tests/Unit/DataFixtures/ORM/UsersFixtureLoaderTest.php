<?php

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureDataInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoadCheckerInterface;
use DG\OpenticketBundle\DataFixtures\ORM\UsersFixtureLoader;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UsersFixtureLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UsersFixtureLoader
     */
    private $fixture;

    /**
     * @var EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $managerMock;

    /**
     * @var UserPasswordEncoderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $passwordEncoderMock;

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
        $this->passwordEncoderMock = $this->getMockForAbstractClass('Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface');
        $this->managerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->fixture = new UsersFixtureLoader($this->fixtureDataMock, $this->fixtureLoadCheckerMock, $this->managerMock, $this->passwordEncoderMock);
    }

    protected function tearDown()
    {
        $this->fixtureLoadCheckerMock = null;
        $this->fixtureDataMock = null;
        $this->passwordEncoderMock = null;
        $this->fixture = null;
        $this->managerMock = null;
    }

    public function testLoadNoDuplicate()
    {
        $this->fixtureLoadCheckerMock->expects($this->once())->method('hasBeenLoaded')->willReturn(false);
        $this->fixtureDataMock->expects($this->once())->method('getData')->willReturn([
            [
                'username' => 'admin', 'password' => 'admin_password', 'roles' => ['ROLE_ADMIN'], 'email' => 'admin@mail.com'
            ],
            [
                'username' => 'test', 'password' => 'test_password', 'roles' => ['ROLE_TEST'], 'email' => 'test@mail.com'
            ],
        ]);

        $this->passwordEncoderMock->expects($this->exactly(2))->method('encodePassword')
            ->withConsecutive(
                [$this->isInstanceOf('DG\OpenticketBundle\Model\User'), 'admin_password'],
                [$this->isInstanceOf('DG\OpenticketBundle\Model\User'), 'test_password']
            )
            ->willReturn('encoded_password');

        $this->managerMock->expects($this->exactly(2))->method('persist')->withConsecutive(
            [
                $this->callback(function (User $user) {
                    $this->assertEquals('admin', $user->getUsername());
                    $this->assertEquals(['ROLE_ADMIN'], $user->getRoles());
                    $this->assertEquals('encoded_password', $user->getPassword());
                    $this->assertEquals(false, $user->isDeleted());

                    return true;
                })
            ],
            [
                $this->callback(function (User $user) {
                    $this->assertEquals('test', $user->getUsername());
                    $this->assertEquals(['ROLE_TEST'], $user->getRoles());
                    $this->assertEquals('encoded_password', $user->getPassword());
                    $this->assertEquals(false, $user->isDeleted());

                    return true;
                })
            ]
        );

        $this->managerMock->expects($this->once())->method('flush');

        $this->fixture->load();
    }

    /**
     * @expectedException \DG\OpenticketBundle\Exception\DuplicateException
     */
    public function testLoadDuplicate()
    {
        $this->fixtureLoadCheckerMock->expects($this->once())->method('hasBeenLoaded')->willReturn(true);

        $this->fixtureDataMock->expects($this->never())->method('getData');
        $this->passwordEncoderMock->expects($this->never())->method('encodePassword');
        $this->managerMock->expects($this->never())->method('persist');
        $this->managerMock->expects($this->never())->method('flush');

        $this->fixture->load();
    }
}
