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
class UserTicketRelationTest extends WebTestCase
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

    public function testUserTicketRelation()
    {
//        $user = User::create()
//            ->setUsername('test_user')
//            ->setPassword('password')
//            ->setSalt('salt')
//            ->setRoles(['ROLE_ADMIN'])
//            ->setEmail('test@email.com')
//            ->setDeleted(false);
//
//        $this->manager->persist($user);
//        $this->manager->flush();
//        $this->manager->clear();
    }
}
 