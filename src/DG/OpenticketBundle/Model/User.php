<?php
/**
 * User: Dmitry Grachikov
 * Date: 07.11.15
 * Time: 22:32
 */

namespace DG\OpenticketBundle\Model;


use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\Annotation as Doctrine;

/**
 * User model
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="username", unique=true, length=200)
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(name="email", length=255)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(name="salt", length=200)
     *
     * @var string
     */
    private $salt;

    /**
     * @ORM\Column(name="password", length=200)
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(name="roles", type="array")
     *
     * @var string[]
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="createdBy")
     *
     * @var Ticket[]
     */
    private $createdTickets;

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param \string[] $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return Ticket[]
     */
    public function getCreatedTickets()
    {
        return $this->createdTickets;
    }

    /**
     * @param Ticket[] $createdTickets
     */
    public function setCreatedTickets($createdTickets)
    {
        $this->createdTickets = $createdTickets;
    }

    public function addCreatedTicket(Ticket $createdTicket)
    {
        $this->createdTickets[] = $createdTicket;
    }
}