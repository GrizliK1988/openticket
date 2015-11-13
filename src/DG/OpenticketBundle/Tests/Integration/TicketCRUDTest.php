<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TicketCRUDTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCRUDTest extends WebTestCase
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
        $users = $repo->findBy(['username' => 'test_username_for_ticket']);

        if (count($users)) {
            $this->manager->remove($users[0]);
            $this->manager->flush();
        }
    }

    public function testTicketCRUD()
    {
        $creator = new User();
        $creator->setUsername('test_username_for_ticket');
        $creator->setEmail('test_username_for_ticket@mail.com');
        $creator->setPassword('password');
        $creator->setRoles(['ROLE']);
        $creator->setSalt('salt');

        $ticket = new Ticket();
        $ticket->setCreatedBy($creator);
        $ticket->setLastModifiedBy($creator);

        $this->manager->persist($creator);
        $this->manager->persist($ticket);
        $this->manager->flush();

        $userId = $creator->getId();

        $this->manager->clear();

        $ticketRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket');
        /** @var Ticket[] $userTickets */
        $userTickets = $ticketRepo->findBy(['createdBy' => $userId]);

        $this->assertEquals(1, count($userTickets));
        $this->assertEquals($creator->getId(), $userTickets[0]->getLastModifiedBy()->getId());
    }
}
 