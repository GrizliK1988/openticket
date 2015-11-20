<?php


namespace DG\OpenticketBundle\Model;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ticket_category_relations")
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCategory
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Ticket")
     * @ORM\JoinColumn(name="ticket_id", nullable=false)
     *
     * @var Ticket
     */
    private $ticket;

    /**
     * @ORM\Id
     * @ORM\OneToMany(targetEntity="\DG\OpenticketBundle\Model\Ticket\Category", mappedBy="ticketCategoryRelation")
     * @ORM\Column(name="category_id", type="integer")
     *
     * @var int
     */
    private $categoryId;

    /**
     * @return static
     */
    public static function create()
    {
        return new static;
    }

    /**
     * @return Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param Ticket $ticket
     * @return TicketCategory
     */
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     * @return TicketCategory
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        return $this;
    }
}