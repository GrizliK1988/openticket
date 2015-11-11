<?php
/**
 * User: Dmitry Grachikov
 * Date: 09.11.15
 * Time: 20:32
 */

namespace DG\OpenticketBundle\Model;


use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket model
 *
 * @ORM\Entity
 * @ORM\Table(name="tickets")
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class Ticket
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
     * @ORM\Column(type="datetimetz")
     *
     * @var \DateTime
     */
    private $createdTime;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="createdTickets")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     *
     * @var User
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetimetz")
     *
     * @var \DateTime
     */
    private $lastModifiedTime;

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * @param \DateTime $createdTime
     */
    public function setCreatedTime(\DateTime $createdTime)
    {
        $this->createdTime = $createdTime;
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
     * @return \DateTime
     */
    public function getLastModifiedTime()
    {
        return $this->lastModifiedTime;
    }

    /**
     * @param \DateTime $lastModifiedTime
     */
    public function setLastModifiedTime(\DateTime $lastModifiedTime)
    {
        $this->lastModifiedTime = $lastModifiedTime;
    }
}