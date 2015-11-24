<?php

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\ORM\UsersFixture;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UsersFixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UsersFixture
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

    protected function setUp()
    {
        $this->passwordEncoderMock = $this->getMockForAbstractClass('Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface');
        $this->managerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->fixture = new UsersFixture($this->managerMock, $this->passwordEncoderMock);
    }

    protected function tearDown()
    {
        $this->fixture = null;
        $this->managerMock = null;
    }

    public function testLoad()
    {
        $this->passwordEncoderMock->expects($this->once())->method('encodePassword')
            ->with($this->isInstanceOf('DG\OpenticketBundle\Model\User'), 'admin')
            ->willReturn('encoded_password');

        $this->managerMock->expects($this->once())->method('persist')->with($this->callback(function ($user) {
            /** @var User $user */
            $this->assertInstanceOf('DG\OpenticketBundle\Model\User', $user);
            $this->assertEquals('admin', $user->getUsername());
            $this->assertEquals(['ROLE_ADMIN'], $user->getRoles());
            $this->assertEquals('encoded_password', $user->getPassword());
            $this->assertEquals(false, $user->isDeleted());

            return true;
        }));

        $this->managerMock->expects($this->once())->method('flush');

        $this->fixture->load();
    }
}
