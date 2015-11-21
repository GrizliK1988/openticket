<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketSubscriptionTest extends AbstractORMTest
{
    public function testSubscriptionCreateRead()
    {
        $creator = User::create()
            ->setUsername('test_ticket_creator_username')
            ->setDeleted(false)
            ->setEmail('test_ticket_creator_username')
            ->setPassword('password')
            ->setRoles(['ROLE'])
            ->setSalt('salt');

        $subscriber = User::create()
            ->setUsername('test_ticket_subscriber_username')
            ->setDeleted(false)
            ->setEmail('test_ticket_subscriber_username')
            ->setPassword('password')
            ->setRoles(['ROLE'])
            ->setSalt('salt');

        $category = Ticket\Category::create();

        $status = Ticket\Status::create();

        $ticket = Ticket::create()
            ->setCategory($category)
            ->setStatus($status)
            ->setCreatedBy($creator)
            ->setLastModifiedBy($creator);

        $subscription = Ticket\Subscription::create()
            ->setTicket($ticket)
            ->setUser($subscriber);

        $this->persist($creator);
        $this->persist($subscriber);
        $this->persist($ticket);
        $this->persist($subscription);
        $this->persist($category);
        $this->persist($status);
        $this->manager->flush();

        $this->manager->clear();
        $repo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Subscription');

        /** @var Ticket\Subscription[] $foundSubscriptions */
        $foundSubscriptions = $repo->findBy(['user' => $subscriber]);

        $queriesCount = $this->queriesCountLogger->queriesCount;

        $this->assertEquals(1, count($foundSubscriptions));
        $this->assertEquals($ticket->getId(), $foundSubscriptions[0]->getTicket()->getId());
        $this->assertEquals($subscriber->getId(), $foundSubscriptions[0]->getUser()->getId());
        $this->assertEquals($queriesCount, $this->queriesCountLogger->queriesCount);
    }
}
