<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketMessageTest extends AbstractORMTest
{
    public function testMessageCreateReadUpdate()
    {
        $user = User::create()
            ->setUsername('test_ticket_message_username')
            ->setDeleted(false)
            ->setEmail('test_ticket_message_username')
            ->setPassword('password')
            ->setRoles(['ROLE'])
            ->setSalt('salt')
        ;

        $category = Ticket\Category::create();

        $status = Ticket\Status::create();

        $ticket = Ticket::create()
            ->setCategory($category)
            ->setStatus($status)
            ->setCreatedBy($user)
            ->setLastModifiedBy($user);

        $message = Ticket\Message::create()
            ->setType(Ticket\Message::EXTERNAL)
            ->setCreatedBy($user)
            ->setTicket($ticket)
            ->setTitle('title')
            ->setText('text');

        $this->persist($user);
        $this->persist($ticket);
        $this->persist($message);
        $this->persist($category);
        $this->persist($status);
        $this->manager->flush();

        $message->setText('new text');
        $this->persist($message);
        $this->manager->flush();

        $this->manager->clear();
        $repo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Message');

        /** @var Ticket\Message[] $foundMessages */
        $foundMessages = $repo->findBy(['title' => 'title']);
        $this->assertEquals(1, count($foundMessages));
        $this->assertEquals('title', $foundMessages[0]->getTitle());
        $this->assertEquals('new text', $foundMessages[0]->getText());
    }
}
