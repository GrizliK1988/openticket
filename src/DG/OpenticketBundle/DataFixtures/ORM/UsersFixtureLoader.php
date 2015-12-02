<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureDataInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoadCheckerInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface;
use DG\OpenticketBundle\Exception\DuplicateException;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UsersFixtureLoader implements FixtureLoaderInterface
{
    const DIC_NAME = 'dg_openticket.db_fixture.users';

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
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UsersFixtureLoader constructor.
     * @param FixtureDataInterface $fixtureData
     * @param FixtureLoadCheckerInterface $fixtureLoadChecker
     * @param EntityManager $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(FixtureDataInterface $fixtureData,
                                FixtureLoadCheckerInterface $fixtureLoadChecker,
                                EntityManager $entityManager,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->fixtureData = $fixtureData;
        $this->fixtureLoadChecker = $fixtureLoadChecker;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Performs fixture loading: persists and flushes fixture
     *
     * @throws DuplicateException
     */
    public function load()
    {
        $hasBeenLoaded = $this->fixtureLoadChecker->hasBeenLoaded();
        if ($hasBeenLoaded) {
            throw new DuplicateException('users_fixture_already_loaded');
        } else {
            foreach ($this->fixtureData->getData() as $userFixture) {
                $user = User::create()
                    ->setUsername($userFixture['username'])
                    ->setRoles($userFixture['roles'])
                    ->setSalt($salt = uniqid((string)mt_rand(), true))
                    ->setEmail($userFixture['email']);

                $encodedPassword = $this->passwordEncoder->encodePassword($user, $userFixture['password']);
                $user->setPassword($encodedPassword);
                $this->entityManager->persist($user);
            }

            $this->entityManager->flush();
        }
    }
}