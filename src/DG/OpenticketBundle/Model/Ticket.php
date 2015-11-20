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
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="lastModifiedTickets")
     * @ORM\JoinColumn(name="last_modified_by", referencedColumnName="id", nullable=false)
     *
     * @var User
     */
    private $lastModifiedBy;

    /**
     * @ORM\ManyToOne(targetEntity="\DG\OpenticketBundle\Model\Ticket\Category")
     * @ORM\JoinColumn(name="category_id", nullable=false)
     *
     * @var Ticket\Category
     */
    private $category;

    public static function create()
    {
        return new static;
    }

    public function __construct()
    {
        $this->createdTime = new \DateTime();
        $this->lastModifiedTime = new \DateTime();
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     * @return $this
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return $this
     */
    public function setLastModifiedTime(\DateTime $lastModifiedTime)
    {
        $this->lastModifiedTime = $lastModifiedTime;
        return $this;
    }

    /**
     * @return User
     */
    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }

    /**
     * @param User $lastModifiedBy
     * @return $this
     */
    public function setLastModifiedBy(User $lastModifiedBy)
    {
        $this->lastModifiedBy = $lastModifiedBy;
        return $this;
    }

    /**
     * @return Ticket\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Ticket\Category $category
     * @return Ticket
     */
    public function setCategory(Ticket\Category $category)
    {
        $this->category = $category;
        return $this;
    }
}