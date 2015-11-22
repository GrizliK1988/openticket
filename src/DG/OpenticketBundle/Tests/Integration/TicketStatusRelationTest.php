<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketStatusRelationTest extends AbstractORMTest
{
    public function testStatusCreateReadUpdate()
    {
        $creator = User::create()
            ->setUsername('test_username_for_ticket')
            ->setPassword('password')
            ->setSalt('salt')
            ->setRoles(['ROLE'])
            ->setEmail('test_username_for_ticket@mail.com')
            ->setDeleted(false);

        $category = Ticket\Category::create();
        $status = Ticket\Status::create();

        $ticket = Ticket::create()
            ->setStatus($status)
            ->setCategory($category)
            ->setCreatedBy($creator)
            ->setLastModifiedBy($creator);

        $this->persist($category);
        $this->persist($status);
        $this->persist($creator);
        $this->persist($ticket);
        $this->manager->flush();
        $this->manager->clear();

        $statusRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Status');

        /** @var Ticket\Status[] $foundNotDeletedStatuses */
        $foundNotDeletedStatuses = $statusRepo->findBy(['deleted' => false]);
        $this->assertEquals(1, count($foundNotDeletedStatuses));
        $this->assertGreaterThan(0, $foundNotDeletedStatuses[0]->getId());
        $this->assertFalse($foundNotDeletedStatuses[0]->isDeleted());

        $foundNotDeletedStatuses[0]->setDeleted(true);
        $this->persist($foundNotDeletedStatuses[0]);
        $this->manager->flush();
        $this->manager->clear();

        /** @var Ticket\Status[] $foundDeletedStatuses */
        $foundDeletedStatuses = $statusRepo->findBy(['deleted' => true]);
        $this->assertEquals(1, count($foundDeletedStatuses));
        $this->assertTrue($foundDeletedStatuses[0]->isDeleted());
        $this->manager->clear();

        $ticketRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket');

        $foundTickets = $ticketRepo->findBy(['status' => $foundDeletedStatuses[0]]);
        $this->assertEquals(1, count($foundTickets));
    }
}
 