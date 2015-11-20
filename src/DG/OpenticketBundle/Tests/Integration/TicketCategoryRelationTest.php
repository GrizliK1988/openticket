<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\TicketCategory;
use DG\OpenticketBundle\Model\User;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCategoryRelationTest extends AbstractORMTest
{
    public function testRelation()
    {
        $category = Ticket\Category::create()
            ->setId(185)
            ->setLocale('en')
            ->setName('Category');

        $user = User::create()
            ->setEmail('email')
            ->setPassword('password')
            ->setRoles(['ROLE'])
            ->setSalt('salt')
            ->setUsername('username');

        $ticket = Ticket::create()
            ->setCreatedBy($user)
            ->setLastModifiedBy($user);

        $this->persist($user);
        $this->persist($ticket);
        $this->persist($category);
        $this->manager->flush();

        $ticketCategoryRelation = TicketCategory::create()
            ->setCategoryId($category->getId())
            ->setTicket($ticket);
        $this->manager->persist($ticketCategoryRelation);
        $this->manager->flush();
        $this->manager->clear();

        $categoryRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Category');
        /** @var Ticket\Category[] $foundCategories */
        $foundCategories = $categoryRepo->findBy(['id' => $ticketCategoryRelation->getCategoryId()]);
        $this->assertEquals(1, count($foundCategories));
        $this->assertEquals('en', $foundCategories[0]->getLocale());

        $ticketRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket');
        $ticketCategoryRelationRepo = $this->manager->getRepository('DGOpenticketBundle:TicketCategory');

        /** @var TicketCategory $foundTicketCategoryRelation */
        $foundTicketCategoryRelation = $ticketCategoryRelationRepo->findOneBy(['categoryId' => $category->getId()]);
        $this->assertNotNull($foundTicketCategoryRelation);
        $this->assertEquals($ticket->getId(), $foundTicketCategoryRelation->getTicket()->getId());

        $this->manager->remove($foundTicketCategoryRelation);
        $this->manager->flush();
    }
}
 