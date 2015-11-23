<?php

namespace DG\OpenticketBundle\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureInterface;
use DG\OpenticketBundle\Model\Ticket\Status;
use DG\OpenticketBundle\Model\Translation;
use Doctrine\ORM\EntityManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketStatusFixture implements FixtureInterface
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Performs fixture loading: persists and flushes fixture
     *
     * @return void
     */
    public function load()
    {
        $this->manager->persist($statusOpened = Status::create());
        $this->manager->persist($statusInProgress = Status::create());
        $this->manager->persist($statusClosed = Status::create());

        $this->manager->flush();

        $this->manager->persist(Translation::create()
            ->setDomain('ticket_status')
            ->setLocale('en')
            ->setKey($statusOpened->getId())
            ->setTranslation('Opened')
        );
        $this->manager->persist(Translation::create()
            ->setDomain('ticket_status')
            ->setLocale('ru')
            ->setKey($statusOpened->getId())
            ->setTranslation('Открыт')
        );

        $this->manager->persist(Translation::create()
            ->setDomain('ticket_status')
            ->setLocale('en')
            ->setKey($statusInProgress->getId())
            ->setTranslation('In progress')
        );
        $this->manager->persist(Translation::create()
            ->setDomain('ticket_status')
            ->setLocale('ru')
            ->setKey($statusInProgress->getId())
            ->setTranslation('Взят в работу')
        );

        $this->manager->persist(Translation::create()
            ->setDomain('ticket_status')
            ->setLocale('en')
            ->setKey($statusClosed->getId())
            ->setTranslation('Closed')
        );
        $this->manager->persist(Translation::create()
            ->setDomain('ticket_status')
            ->setLocale('ru')
            ->setKey($statusClosed->getId())
            ->setTranslation('Закрыт')
        );

        $this->manager->flush();
    }
}