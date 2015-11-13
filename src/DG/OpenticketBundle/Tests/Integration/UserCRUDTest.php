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

    public function testUserWithTicketCRUD()
    {
        $user = new User();
        $user->setUsername('test_user');
        $user->setPassword('password');
        $user->setSalt('salt');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('test@email.com');

        $ticket = new Ticket();
        $ticket->setCreatedBy($user);

        $user->addCreatedTicket($ticket);

        $this->manager->persist($user);
        $this->manager->flush();

        $this->assertGreaterThan(0, $user->getId());

        /** @var User[] $users */
        $repo = $this->manager->getRepository('DGOpenticketBundle:User');
        $users = $repo->findBy(['username' => 'test_user']);
        $this->assertNotEmpty($users);
        $this->assertEquals('test_user', $users[0]->getUsername());
        $this->assertEquals('password', $users[0]->getPassword());
        $this->assertEquals('salt', $users[0]->getSalt());
        $this->assertEquals(['ROLE_ADMIN'], $users[0]->getRoles());
        $this->assertEquals('test@email.com', $users[0]->getEmail());
        $this->assertGreaterThan(new \DateTime('-2 seconds'), $users[0]->getCreatedTime());

        $tickets = $users[0]->getCreatedTickets();
        $this->assertEquals(1, count($tickets));
        $ticketId = $tickets[0]->getId();

        /** @var Ticket[] $foundTickets */
        $foundTickets = $this->manager->getRepository('DGOpenticketBundle:Ticket')->findBy(['createdBy' => $user->getId()]);
        $this->assertEquals(1, count($foundTickets));
        $this->assertEquals($ticketId, $foundTickets[0]->getId());
        $this->assertGreaterThan(new \DateTime('-2 seconds'), $foundTickets[0]->getCreatedTime());
        $this->assertGreaterThan(new \DateTime('-2 seconds'), $foundTickets[0]->getLastModifiedTime());
        $this->assertEquals($user, $foundTickets[0]->getCreatedBy());

        $this->manager->remove($users[0]);
        $this->manager->flush();

        $users = $this->manager->getRepository('DGOpenticketBundle:User')->findBy(['username' => 'test_user']);
        $this->assertEmpty($users);

        $ticket = $this->manager->getRepository('DGOpenticketBundle:Ticket')->find($ticketId);
        $this->assertNull($ticket);
    }

    public function testTicketDelete()
    {
        $user = new User();
        $user->setUsername('test_user');
        $user->setPassword('password');
        $user->setSalt('salt');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('test@email.com');

        $ticket = new Ticket();
        $ticket->setCreatedBy($user);
        $ticket->setLastModifiedTime(new \DateTime());

        $user->setCreatedTickets([$ticket]);

        $this->manager->persist($user);
        $this->manager->flush();

        /** @var User[] $users */
        $repo = $this->manager->getRepository('DGOpenticketBundle:User');
        $users = $repo->findBy(['username' => 'test_user']);

        $tickets = $users[0]->getCreatedTickets();
        $ticketId = $tickets[0]->getId();

        $this->manager->remove($tickets[0]);
        $this->manager->flush();

        $users = $this->manager->getRepository('DGOpenticketBundle:User')->findBy(['username' => 'test_user']);
        $this->assertNotEmpty($users);

        $ticket = $this->manager->getRepository('DGOpenticketBundle:Ticket')->find($ticketId);
        $this->assertNull($ticket);

        $this->manager->remove($users[0]);
        $this->manager->flush();
    }
}
 