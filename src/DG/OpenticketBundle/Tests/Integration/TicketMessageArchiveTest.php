<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketMessageArchiveTest extends AbstractORMTest
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

        $createdDate = new \DateTime('-2 days');
        $archiveMessage = Ticket\MessageArchive::create()
            ->setVersion(2)
            ->setMessage($message)
            ->setCreatedTime($createdDate)
            ->setType(Ticket\Message::EXTERNAL)
            ->setCreatedBy($user)
            ->setTicket($ticket)
            ->setTitle('title archive')
            ->setText('text archive');

        $this->persist($user);
        $this->persist($ticket);
        $this->persist($message);
        $this->persist($archiveMessage);
        $this->persist($category);
        $this->persist($status);
        $this->manager->flush();

        $this->manager->clear('\DG\OpenticketBundle\Model\Ticket\MessageArchive');
        $repo = $this->manager->getRepository('DGOpenticketBundle:Ticket\MessageArchive');

        /** @var Ticket\MessageArchive[] $foundMessages */
        $foundMessages = $repo->findBy(['message' => $message]);
        $this->assertEquals(1, count($foundMessages));
        $this->assertEquals('title archive', $foundMessages[0]->getTitle());
        $this->assertEquals('text archive', $foundMessages[0]->getText());
        $this->assertEquals($createdDate, $foundMessages[0]->getCreatedTime());
    }
}
