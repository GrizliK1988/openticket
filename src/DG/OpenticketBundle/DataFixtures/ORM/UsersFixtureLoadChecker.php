<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureDataInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoadCheckerInterface;
use Doctrine\ORM\EntityManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UsersFixtureLoadChecker implements FixtureLoadCheckerInterface
{
    /**
     * @var FixtureDataInterface
     */
    private $fixtureData;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UsersFixtureLoader constructor.
     * @param FixtureDataInterface $fixtureData
     * @param EntityManager $entityManager
     */
    public function __construct(FixtureDataInterface $fixtureData,
                                EntityManager $entityManager)
    {
        $this->fixtureData = $fixtureData;
        $this->entityManager = $entityManager;
    }

    /**
     * Says whether fixture has been already loaded or not
     */
    public function hasBeenLoaded(): bool
    {
        $userRepository = $this->entityManager->getRepository('DGOpenticketBundle:User');
        foreach ($this->fixtureData->getData() as $fixture) {
            $existedUser = $userRepository->findOneBy(['username' => $fixture['username']]);
            if ($existedUser !== null) {
                return true;
            }
        }

        return false;
    }
}