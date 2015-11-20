<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UserTest extends AbstractORMTest
{
    public function testUserCRUD()
    {
        $user = User::create()
            ->setUsername('test_user')
            ->setPassword('password')
            ->setSalt('salt')
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('test@email.com')
            ->setDeleted(false);

        $this->persist($user);
        $this->manager->flush();
        $this->manager->clear();

        /** @var User[] $users */
        $repo = $this->manager->getRepository('DGOpenticketBundle:User');
        $users = $repo->findBy(['username' => 'test_user']);
        $this->assertNotEmpty($users);
        $this->assertEquals('test_user', $users[0]->getUsername());
        $this->assertEquals('password', $users[0]->getPassword());
        $this->assertEquals('salt', $users[0]->getSalt());
        $this->assertEquals(['ROLE_ADMIN'], $users[0]->getRoles());
        $this->assertEquals('test@email.com', $users[0]->getEmail());
        $this->assertEquals(false, $users[0]->isDeleted());
        $this->assertGreaterThan(new \DateTime('-2 seconds'), $users[0]->getCreatedTime());

        $users[0]
            ->setDeleted(true)
            ->setSalt('salt_new')
            ->setRoles(['ROLE_USER'])
            ->setPassword('password_new')
            ->setEmail('email_new')
            ->setUsername('username_new')
        ;

        $this->persist($users[0]);
        $this->manager->flush();
        $this->manager->clear();

        /** @var User $foundUser */
        $foundUser = $this->manager->getRepository('DGOpenticketBundle:User')->find($users[0]->getId());
        $this->assertNotEmpty($foundUser);

        $this->assertEquals(true, $foundUser->isDeleted());
        $this->assertEquals('salt_new', $foundUser->getSalt());
        $this->assertEquals(['ROLE_USER'], $foundUser->getRoles());
        $this->assertEquals('password_new', $foundUser->getPassword());
        $this->assertEquals('email_new', $foundUser->getEmail());
        $this->assertEquals('username_new', $foundUser->getUsername());
    }
}
 