<?php

namespace DG\OpenticketBundle\Tests\Integration;
use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCategoryTest extends WebTestCase
{
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
        $this->manager = $this->manager->create($this->manager->getConnection(), $this->manager->getConfiguration());

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

    public function testCategoryCreateReadUpdate()
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

        $categoryRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Category');

        /** @var Ticket\Category[] $foundCategoriesByLocale */
        $foundCategoriesByLocale = $categoryRepo->findBy(['locale' => 'en']);
        $this->assertEquals(1, count($foundCategoriesByLocale));
        $this->assertEquals(1, $foundCategoriesByLocale[0]->getId());
        $this->assertEquals('en', $foundCategoriesByLocale[0]->getLocale());
        $this->assertEquals('Bug', $foundCategoriesByLocale[0]->getName());

        /** @var Ticket\Category $foundCategoryByPK */
        $foundCategoryByPK = $categoryRepo->find(['id' => 1, 'locale' => 'en']);
        $this->assertNotNull($foundCategoryByPK);

        $foundCategoriesByName = $categoryRepo->findBy(['name' => 'Bug']);
        $this->assertEquals(1, count($foundCategoriesByName));

        $foundCategoryByPK->setName('Improvement');
        $this->manager->persist($foundCategoryByPK);
        $this->manager->flush();
        $this->manager->clear();

        /** @var Ticket\Category $foundUpdatedCategoryByPK */
        $foundUpdatedCategoryByPK = $categoryRepo->find(['id' => 1, 'locale' => 'en']);
        $this->assertEquals('Improvement', $foundUpdatedCategoryByPK->getName());

        $foundUpdatedCategoryByPK->setLocale(str_pad('', 20, 'A')); //too long locale
        try {
            $this->manager->persist($foundUpdatedCategoryByPK);
            $this->manager->flush();
            $this->fail();
        } catch (\Exception $exception) {
            $this->assertInstanceOf('Doctrine\DBAL\Exception\DriverException', $exception);
        }
    }
}
 