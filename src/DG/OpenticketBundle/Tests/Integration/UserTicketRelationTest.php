<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UserTicketRelationTest extends AbstractORMTest
{
    public function testRelation()
    {
        $creator = User::create()
            ->setUsername('test_username_for_ticket')
            ->setPassword('password')
            ->setSalt('salt')
            ->setRoles(['ROLE'])
            ->setEmail('test_username_for_ticket@mail.com')
            ->setDeleted(false);

        $this->persist($creator);
        $this->manager->flush();
        $creatorUserId = $creator->getId();

        $category = Ticket\Category::create();

        $ticket = Ticket::create()
            ->setCreatedBy($creator)
            ->setCategory($category)
            ->setLastModifiedBy($creator);

        $this->persist($category);
        $this->persist($ticket);
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
 