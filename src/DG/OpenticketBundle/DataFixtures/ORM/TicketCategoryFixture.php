<?php

namespace DG\OpenticketBundle\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureInterface;
use DG\OpenticketBundle\Model\Ticket\Category;
use DG\OpenticketBundle\Model\Translation;
use Doctrine\ORM\EntityManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCategoryFixture implements FixtureInterface
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
        $this->manager->persist($categoryBug = Category::create());
        $this->manager->persist($categoryImprovement = Category::create());
        $this->manager->persist($categoryQuestion = Category::create());

        $this->manager->flush();

        $this->manager->persist(Translation::create()
            ->setDomain('ticket_category')
            ->setLocale('en')
            ->setKey($categoryBug->getId())
            ->setTranslation('Bug')
        );
        $this->manager->persist(Translation::create()
            ->setDomain('ticket_category')
            ->setLocale('ru')
            ->setKey($categoryBug->getId())
            ->setTranslation('Ошибка')
        );

        $this->manager->persist(Translation::create()
            ->setDomain('ticket_category')
            ->setLocale('en')
            ->setKey($categoryImprovement->getId())
            ->setTranslation('Improvement')
        );
        $this->manager->persist(Translation::create()
            ->setDomain('ticket_category')
            ->setLocale('ru')
            ->setKey($categoryImprovement->getId())
            ->setTranslation('Улучшение')
        );

        $this->manager->persist(Translation::create()
            ->setDomain('ticket_category')
            ->setLocale('en')
            ->setKey($categoryQuestion->getId())
            ->setTranslation('Question')
        );
        $this->manager->persist(Translation::create()
            ->setDomain('ticket_category')
            ->setLocale('ru')
            ->setKey($categoryQuestion->getId())
            ->setTranslation('Вопрос')
        );

        $this->manager->flush();
    }
}