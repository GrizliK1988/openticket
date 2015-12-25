<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureDataInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoadCheckerInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface;
use DG\OpenticketBundle\Event\Fixture\BeforeLoadEvent;
use DG\OpenticketBundle\Event\Fixture\RecordLoadEvent;
use DG\OpenticketBundle\Exception\DuplicateException;
use DG\OpenticketBundle\Model\Ticket\Category;
use DG\OpenticketBundle\Model\Translation;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCategoriesFixtureLoader implements FixtureLoaderInterface
{
    const DIC_NAME = 'dg_openticket.db_fixture.ticket_categories';

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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param FixtureDataInterface $fixtureData
     * @param FixtureLoadCheckerInterface $fixtureLoadChecker
     * @param EntityManager $manager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FixtureDataInterface $fixtureData,
                                FixtureLoadCheckerInterface $fixtureLoadChecker,
                                EntityManager $manager,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->fixtureData = $fixtureData;
        $this->fixtureLoadChecker = $fixtureLoadChecker;
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Performs fixture loading: persists and flushes fixture
     *
     * @throws DuplicateException
     */
    public function load()
    {
        if ($this->fixtureLoadChecker->hasBeenLoaded()) {
            throw new DuplicateException('ticket_categories_fixture_already_loaded');
        }

        $fixtureData = $this->fixtureData->getData();
        $this->eventDispatcher->dispatch(BeforeLoadEvent::NAME, new BeforeLoadEvent(count($fixtureData)));

        foreach ($fixtureData as $translations) {
            $category = Category::create();
            $this->manager->persist($category);
            $this->manager->flush();

            $this->eventDispatcher->dispatch(RecordLoadEvent::NAME, new RecordLoadEvent());

            foreach ($translations as $translation) {
                $this->manager->persist(Translation::create()
                    ->setKey($category->getId())
                    ->setLocale($translation['locale'])
                    ->setDomain('ticket_category')
                    ->setTranslation($translation['translation']));
            }
            $this->manager->flush();
        }
    }

    /**
     * Returns name of loader
     *
     * @return string
     */
    public function getName(): \string
    {
        return 'ticket_categories_fixture';
    }

    /**
     * Says has been fixture already loaded
     *
     * @return bool
     */
    public function hasBeenLoaded(): \bool
    {
        return $this->fixtureLoadChecker->hasBeenLoaded();
    }
}