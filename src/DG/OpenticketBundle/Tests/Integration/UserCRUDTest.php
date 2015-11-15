<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserCRUDTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UserCRUDTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $manager;

    protected function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $this->manager = $container->get('doctrine.orm.entity_manager');
    }

    protected function tearDown()
    {
        $repo = $this->manager->getRepository('DGOpenticketBundle:User');
        $users = $repo->findBy(['username' => 'test_user']);

        if (count($users)) {
            $this->manager->remove($users[0]);
            $this->manager->flush();
        }
    }

    public function testUserCRUD()
    {
        $user = User::create()
            ->setUsername('test_user')
            ->setPassword('password')
            ->setSalt('salt')
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('test@email.com')
            ->setDeleted(false);

        $this->manager->persist($user);
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

        $this->manager->remove($users[0]);
        $this->manager->flush();
        $this->manager->clear();

        $users = $this->manager->getRepository('DGOpenticketBundle:User')->findBy(['username' => 'test_user']);
        $this->assertEmpty($users);
    }
}
 