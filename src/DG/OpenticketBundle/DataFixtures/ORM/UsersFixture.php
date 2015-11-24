<?php

namespace DG\OpenticketBundle\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureInterface;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UsersFixture implements FixtureInterface
{
    const DIC_NAME = 'dg_openticket.db_fixture.users';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UsersFixture constructor.
     * @param EntityManager $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManager $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Performs fixture loading: persists and flushes fixture
     *
     * @return void
     */
    public function load()
    {
        $password = 'admin';
        $salt = uniqid(mt_rand(), true);

        $user = User::create()
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setSalt($salt)
            ->setEmail('admin@mail.com');

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);

        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}