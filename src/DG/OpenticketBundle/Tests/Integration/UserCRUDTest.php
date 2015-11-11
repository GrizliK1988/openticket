<?php
/**
 * User: Dmitry Grachikov
 * Date: 08.11.15
 * Time: 13:15
 */

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserCRUDTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class UserCRUDTest extends WebTestCase
{
    public function testUserCRUD()
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('test_user');
        $user->setPassword('password');
        $user->setSalt('salt');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('test@email.com');

        $client = static::createClient();
        $container = $client->getContainer();

        $manager = $container->get('doctrine.orm.entity_manager');

        $manager->persist($user);
        $manager->flush();

        /** @var User[] $users */
        $users = $manager->getRepository('DGOpenticketBundle:User')->findBy(['username' => 'test_user']);
        $this->assertNotEmpty($users);
        $this->assertEquals($user->getId(), $users[0]->getId());
        $this->assertEquals($user->getUsername(), $users[0]->getUsername());
        $this->assertEquals($user->getPassword(), $users[0]->getPassword());
        $this->assertEquals($user->getSalt(), $users[0]->getSalt());
        $this->assertEquals($user->getRoles(), $users[0]->getRoles());

        $manager->remove($users[0]);
        $manager->flush();

        $users = $manager->getRepository('DGOpenticketBundle:User')->findBy(['username' => 'test_user']);
        $this->assertEmpty($users);

        return ;
    }
}
 