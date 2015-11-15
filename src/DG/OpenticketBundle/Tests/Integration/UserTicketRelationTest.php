<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UserTicketRelationTest extends WebTestCase
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

        /** @var User[] $users */
        $users = $userRepo->findBy(['username' => 'test_username_for_ticket']);
        foreach ($users as $user) {
            $this->manager->remove($user);

            $tickets = $ticketRepo->findBy(['lastModifiedBy' => $user->getId()]);
            foreach ($tickets as $ticket) {
                $this->manager->remove($ticket);
            }
        }

        $this->manager->flush();
    }

    public function testUserDelete()
    {
        $creator = User::create()
            ->setUsername('test_username_for_ticket')
            ->setPassword('password')
            ->setSalt('salt')
            ->setRoles(['ROLE'])
            ->setEmail('test_username_for_ticket@mail.com')
            ->setDeleted(false);

        $this->manager->persist($creator);
        $this->manager->flush();
        $creatorUserId = $creator->getId();

        $ticket = Ticket::create()
            ->setCreatedBy($creator)
            ->setLastModifiedBy($creator);
        $this->manager->persist($ticket);
        $this->manager->flush();

        $this->manager->clear();

        $startQueriesCount = $this->queriesCountLogger->queriesCount;
        $ticketRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket');

        /** @var Ticket[] $userTickets */
        $userTickets = $ticketRepo->findBy(['createdBy' => $creatorUserId]);
        $this->assertEquals($startQueriesCount+1, $this->queriesCountLogger->queriesCount);
        $this->assertEquals(1, count($userTickets));

        $this->assertEquals($creatorUserId, $userTickets[0]->getLastModifiedBy()->getId());
        $this->assertEquals($startQueriesCount+1, $this->queriesCountLogger->queriesCount);

        /** @var Ticket[] $userModifiedTickets */
        $userModifiedTickets = $ticketRepo->findBy(['lastModifiedBy' => $creatorUserId]);
        $this->assertEquals($startQueriesCount+2, $this->queriesCountLogger->queriesCount);
        $this->assertEquals(1, count($userModifiedTickets));

        $this->assertEquals($creatorUserId, $userModifiedTickets[0]->getLastModifiedBy()->getId());
        $this->assertEquals($startQueriesCount+2, $this->queriesCountLogger->queriesCount);
    }
}
 