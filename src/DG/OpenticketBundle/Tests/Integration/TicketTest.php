<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TicketTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var QueriesCountLogger
     */
    private $queriesCountLogger;

    protected function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $this->manager = $container->get('doctrine.orm.entity_manager');
        $this->queriesCountLogger = new QueriesCountLogger();
        $this->manager->getConnection()->getConfiguration()->setSQLLogger($this->queriesCountLogger);
    }

    protected function tearDown()
    {
        $userRepo = $this->manager->getRepository('DGOpenticketBundle:User');
        $ticketRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket');

        foreach (['test_username_for_ticket', 'test_another_username_for_ticket'] as $username) {
            /** @var User[] $users */
            $users = $userRepo->findBy(['username' => $username]);
            foreach ($users as $user) {
                $this->manager->remove($user);

                $tickets = $ticketRepo->findBy(['lastModifiedBy' => $user->getId()]);
                foreach ($tickets as $ticket) {
                    $this->manager->remove($ticket);
                }
            }
        }

        $this->manager->flush();
    }

    public function testTicketCreateReadUpdate()
    {
        $creator = User::create()
            ->setUsername('test_username_for_ticket')
            ->setPassword('password')
            ->setSalt('salt')
            ->setRoles(['ROLE'])
            ->setEmail('test_username_for_ticket@mail.com')
            ->setDeleted(false);

        $anotherUser = User::create()
            ->setUsername('test_another_username_for_ticket')
            ->setPassword('password')
            ->setSalt('salt')
            ->setRoles(['ROLE'])
            ->setEmail('test_another_username_for_ticket@mail.com')
            ->setDeleted(false);

        $ticket = Ticket::create()
            ->setCreatedBy($creator)
            ->setLastModifiedBy($creator);

        $this->manager->persist($creator);
        $this->manager->persist($ticket);
        $this->manager->flush();

        $this->manager->clear();

        $ticketRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket');
        /** @var Ticket $foundTicket */
        $foundTicket = $ticketRepo->find($ticket->getId());
        $this->assertNotNull($foundTicket);
        $this->assertEquals($creator->getId(), $foundTicket->getLastModifiedBy()->getId());
        $this->assertEquals($creator->getId(), $foundTicket->getCreatedBy()->getId());
        $this->assertGreaterThan(new \DateTime('-2 seconds'), $foundTicket->getCreatedTime());
        $this->assertGreaterThan(new \DateTime('-2 seconds'), $foundTicket->getLastModifiedTime());

        $foundTicket->setLastModifiedBy($anotherUser);
        $this->manager->persist($anotherUser);
        $this->manager->persist($foundTicket);
        $this->manager->flush();

        $this->manager->clear();

        /** @var Ticket $foundTicket */
        $foundTicket = $ticketRepo->find($ticket->getId());
        $this->assertNotNull($foundTicket);
        $this->assertEquals($anotherUser->getId(), $foundTicket->getLastModifiedBy()->getId());
    }
}
