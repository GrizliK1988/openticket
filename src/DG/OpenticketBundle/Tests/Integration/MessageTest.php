<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class MessageTest extends WebTestCase
{
    private $persistedEntities = [];

    /**
     * @var EntityManager
     */
    private $manager;

    protected function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $this->manager = $container->get('doctrine.orm.entity_manager');
    }

    protected function tearDown()
    {
        foreach ($this->persistedEntities as $entityData) {
            $repo = $this->manager->getRepository('DGOpenticketBundle:' . $entityData[1]);
            $entities = $repo->findBy(['id' => $entityData[0]->getId()]);
            foreach ($entities as $entity) {
                $this->manager->remove($entity);
            }
        }
        $this->manager->flush();

        $this->manager = null;
    }

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

        $category = Ticket\Category::create()
            ->setId(100)
            ->setLocale('en')
            ->setName('Bug');

        $ticket = Ticket::create()
            ->setCategory($category)
            ->setCreatedBy($user)
            ->setLastModifiedBy($user);

        $message = Ticket\Message::create()
            ->setType(Ticket\Message::EXTERNAL)
            ->setCreatedBy($user)
            ->setTicket($ticket)
            ->setTitle('title')
            ->setText('text');

        $this->persist($user, 'User');
        $this->persist($category, 'Ticket\Category');
        $this->persist($ticket, 'Ticket');
        $this->persist($message, 'Ticket\Message');
        $this->manager->flush();

        $message->setText('new text');
        $this->persist($message, 'Ticket\Message');
        $this->manager->flush();

        $this->manager->clear();
        $repo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Message');

        /** @var Ticket\Message[] $foundMessages */
        $foundMessages = $repo->findBy(['title' => 'title']);
        $this->assertEquals(1, count($foundMessages));
        $this->assertEquals('title', $foundMessages[0]->getTitle());
        $this->assertEquals('new text', $foundMessages[0]->getText());
    }

    private function persist($entity, $entityName)
    {
        $this->manager->persist($entity);
        $this->persistedEntities[] = [$entity, $entityName];
    }
}
