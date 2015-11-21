<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketMessageAttachmentTest extends AbstractORMTest
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

        $attachment1 = Ticket\Message\Attachment::create()
            ->setMessage($message)
            ->setFilePath('/path/to/file1')
            ->setFileSize(100)
            ->setFileType('pdf');

        $attachment2 = Ticket\Message\Attachment::create()
            ->setMessage($message)
            ->setFilePath('/path/to/file2')
            ->setFileSize(200)
            ->setFileType('png');

        $this->persist($user);
        $this->persist($ticket);
        $this->persist($message);
        $this->persist($category);
        $this->persist($status);
        $this->persist($attachment1);
        $this->persist($attachment2);
        $this->manager->flush();

        $this->manager->clear('DG\OpenticketBundle\Model\Ticket\Message\Attachment');
        $repo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Message\Attachment');

        /** @var Ticket\Message\Attachment[] $foundAttachments */
        $foundAttachments = $repo->findBy(['message' => $message], ['id' => 'ASC']);
        $this->assertEquals(2, count($foundAttachments));

        $this->assertEquals('/path/to/file1', $foundAttachments[0]->getFilePath());
        $this->assertEquals(100, $foundAttachments[0]->getFileSize());
        $this->assertEquals('pdf', $foundAttachments[0]->getFileType());

        $this->assertEquals('/path/to/file2', $foundAttachments[1]->getFilePath());
        $this->assertEquals(200, $foundAttachments[1]->getFileSize());
        $this->assertEquals('png', $foundAttachments[1]->getFileType());
    }
}
