<?php

namespace DG\OpenticketBundle\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureInterface;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UsersFixture implements FixtureInterface
{
    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UsersFixture constructor.
     * @param EntityManager $entityManager
     * @param PasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManager $entityManager, PasswordEncoderInterface $passwordEncoder)
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
        $encodedPassword = $this->passwordEncoder->encodePassword($password, $salt);

        $user = User::create()
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setSalt($salt)
            ->setPassword($encodedPassword)
            ->setEmail('admin@mail.com');

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}