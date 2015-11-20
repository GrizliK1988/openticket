<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;

/**
 * Class TicketTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketTest extends AbstractORMTest
{
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

        $category = Ticket\Category::create()
            ->setDeleted(false);

        $ticket = Ticket::create()
            ->setCreatedBy($creator)
            ->setCategory($category)
            ->setLastModifiedBy($creator);

        $this->persist($creator);
        $this->persist($category);
        $this->persist($ticket);
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

        $queriesCount = $this->queriesCountLogger->queriesCount;
        $this->assertNotEmpty($foundTicket->getCategory()->getId());
        $this->assertEquals($queriesCount, $this->queriesCountLogger->queriesCount, 'No query expected to be performed');

        $foundTicket->setLastModifiedBy($anotherUser);
        $this->persist($anotherUser);
        $this->persist($foundTicket);
        $this->manager->flush();

        $this->manager->clear();

        /** @var Ticket $foundTicket */
        $foundTicket = $ticketRepo->find($ticket->getId());
        $this->assertNotNull($foundTicket);
        $this->assertEquals($anotherUser->getId(), $foundTicket->getLastModifiedBy()->getId());
    }
}
