<?php

namespace DG\OpenticketBundle\Model\Ticket;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ticket_statuses")
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class Status
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @ORM\Id
     *
     * @var string
     */
    private $locale;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name;

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
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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