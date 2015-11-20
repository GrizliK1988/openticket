<?php

namespace DG\OpenticketBundle\Model\Ticket;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ticket_categories")
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class Category
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
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $deleted;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->deleted = false;
    }

    public static function create()
    {
        return new static;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param boolean $deleted
     * @return Category
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }
} 