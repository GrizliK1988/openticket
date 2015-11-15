<?php

namespace DG\OpenticketBundle\Tests\Integration;
use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCategoryRelationTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var QueriesCountLogger
     */
    private $queriesCountLogger;

    protected function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $this->manager = $container->get('doctrine.orm.entity_manager');
        $this->queriesCountLogger = new QueriesCountLogger();
        $this->manager->getConnection()->getConfiguration()->setSQLLogger($this->queriesCountLogger);
    }

    protected function tearDown()
    {
        $userRepo = $this->manager->getRepository('DGOpenticketBundle:User');
        $ticketRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket');
        $categoryRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Category');

        /** @var User[] $users */
        $users = $userRepo->findBy(['username' => 'test_ticket_category_username']);
        foreach ($users as $user) {
            $tickets = $ticketRepo->findBy(['createdBy' => $user->getId()]);
            foreach ($tickets as $ticket) {
                $this->manager->remove($ticket);
            }
            $this->manager->remove($user);
        }

        /** @var Ticket\Category[] $categories */
        $categories = $categoryRepo->findBy(['id' => 1]);
        foreach ($categories as $category) {
            $this->manager->remove($category);
        }

        $this->manager->flush();
    }

    public function testTicketCategoryRelation()
    {
        $user = User::create()
            ->setUsername('test_ticket_category_username')
            ->setEmail('test_ticket_category_username@mail.com')
            ->setPassword('password')
            ->setRoles(['ROLE'])
            ->setSalt('salt')
        ;

        $category = Ticket\Category::create()
            ->setId(1)
            ->setLocale('en')
            ->setName('Bug')
        ;

        $ticket = Ticket::create()
            ->setCreatedBy($user)
            ->setLastModifiedBy($user)
            ->setCategory($category)
        ;

        $this->manager->persist($user);
        $this->manager->persist($ticket);
        $this->manager->persist($category);
        $this->manager->flush();
        $this->manager->clear();

        $ticketRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket');
        /** @var Ticket[] $foundTickets */
        $foundTickets = $ticketRepo->findBy(['categoryId' => 1, 'categoryLocale' => 'en']);

        $this->assertEquals(1, count($foundTickets));

        $queryCount = $this->queriesCountLogger->queriesCount;

        $this->assertEquals(1, $foundTickets[0]->getCategory()->getId());
        $this->assertEquals('en', $foundTickets[0]->getCategory()->getLocale());
        $this->assertEquals($queryCount, $this->queriesCountLogger->queriesCount);
    }
}
 