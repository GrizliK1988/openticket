<?php

namespace DG\OpenticketBundle\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureDataInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoadCheckerInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface;
use DG\OpenticketBundle\Exception\DuplicateException;
use DG\OpenticketBundle\Model\Ticket\Status;
use DG\OpenticketBundle\Model\Translation;
use Doctrine\ORM\EntityManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketStatusesFixtureLoader implements FixtureLoaderInterface
{
    const DIC_NAME = 'dg_openticket.db_fixture.ticket_statuses';

    /**
     * @var FixtureDataInterface
     */
    private $fixtureData;

    /**
     * @var FixtureLoadCheckerInterface
     */
    private $fixtureLoadChecker;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @param FixtureDataInterface $fixtureData
     * @param FixtureLoadCheckerInterface $fixtureLoadChecker
     * @param EntityManager $manager
     */
    public function __construct(FixtureDataInterface $fixtureData,
                                FixtureLoadCheckerInterface $fixtureLoadChecker,
                                EntityManager $manager)
    {
        $this->fixtureData = $fixtureData;
        $this->fixtureLoadChecker = $fixtureLoadChecker;
        $this->manager = $manager;
    }

    /**
     * Performs fixture loading: persists and flushes fixture
     *
     * @throws DuplicateException
     */
    public function load()
    {
        if ($this->fixtureLoadChecker->hasBeenLoaded()) {
            throw new DuplicateException('ticket_statuses_fixture_already_loaded');
        }

        foreach ($this->fixtureData->getData() as $translations) {
            $category = Status::create();
            $this->manager->persist($category);
            $this->manager->flush();

            foreach ($translations as $translation) {
                $this->manager->persist(Translation::create()
                    ->setKey($category->getId())
                    ->setLocale($translation['locale'])
                    ->setDomain('ticket_status')
                    ->setTranslation($translation['translation']));
            }
            $this->manager->flush();
        }
    }
}