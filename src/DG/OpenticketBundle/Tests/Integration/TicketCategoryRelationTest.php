<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCategoryRelationTest extends AbstractORMTest
{
    public function testCategoryCreateReadUpdate()
    {
        $creator = User::create()
            ->setUsername('test_username_for_ticket')
            ->setPassword('password')
            ->setSalt('salt')
            ->setRoles(['ROLE'])
            ->setEmail('test_username_for_ticket@mail.com')
            ->setDeleted(false);

        $category = Ticket\Category::create();

        $ticket = Ticket::create()
            ->setCategory($category)
            ->setCreatedBy($creator)
            ->setLastModifiedBy($creator);

        $this->persist($category);
        $this->persist($creator);
        $this->persist($ticket);
        $this->manager->flush();
        $this->manager->clear();

        $categoryRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Category');

        /** @var Ticket\Category[] $foundNotDeletedCategories */
        $foundNotDeletedCategories = $categoryRepo->findBy(['deleted' => false]);
        $this->assertEquals(1, count($foundNotDeletedCategories));
        $this->assertGreaterThan(0, $foundNotDeletedCategories[0]->getId());
        $this->assertFalse($foundNotDeletedCategories[0]->isDeleted());

        $foundNotDeletedCategories[0]->setDeleted(true);
        $this->persist($foundNotDeletedCategories[0]);
        $this->manager->flush();
        $this->manager->clear();

        /** @var Ticket\Category[] $foundDeletedCategories */
        $foundDeletedCategories = $categoryRepo->findBy(['deleted' => true]);
        $this->assertEquals(1, count($foundDeletedCategories));
        $this->assertTrue($foundDeletedCategories[0]->isDeleted());
        $this->manager->clear();

        $ticketRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket');

        $foundTickets = $ticketRepo->findBy(['category' => $foundDeletedCategories[0]]);
        $this->assertEquals(1, count($foundTickets));
    }
}
 