<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureDataInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoadCheckerInterface;
use DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface;
use DG\OpenticketBundle\Event\Fixture\BeforeLoadEvent;
use DG\OpenticketBundle\Event\Fixture\RecordLoadEvent;
use DG\OpenticketBundle\Exception\DuplicateException;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * UsersFixtureLoader constructor.
     * @param FixtureDataInterface $fixtureData
     * @param FixtureLoadCheckerInterface $fixtureLoadChecker
     * @param EntityManager $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FixtureDataInterface $fixtureData,
                                FixtureLoadCheckerInterface $fixtureLoadChecker,
                                EntityManager $entityManager,
                                UserPasswordEncoderInterface $passwordEncoder,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->fixtureData = $fixtureData;
        $this->fixtureLoadChecker = $fixtureLoadChecker;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventDispatcher = $eventDispatcher;
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
            $fixtureData = $this->fixtureData->getData();
            $this->eventDispatcher->dispatch(BeforeLoadEvent::NAME, new BeforeLoadEvent(count($fixtureData)));

            foreach ($fixtureData as $userFixture) {
                $user = User::create()
                    ->setUsername($userFixture['username'])
                    ->setRoles($userFixture['roles'])
                    ->setSalt($salt = uniqid((string)mt_rand(), true))
                    ->setEmail($userFixture['email']);

                $encodedPassword = $this->passwordEncoder->encodePassword($user, $userFixture['password']);
                $user->setPassword($encodedPassword);
                $this->entityManager->persist($user);

                $this->eventDispatcher->dispatch(RecordLoadEvent::NAME, new RecordLoadEvent());
            }

            $this->entityManager->flush();
        }
    }

    /**
     * Returns name of loader
     *
     * @return string
     */
    public function getName(): \string
    {
        return 'users_fixture';
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