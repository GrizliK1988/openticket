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
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }
} 