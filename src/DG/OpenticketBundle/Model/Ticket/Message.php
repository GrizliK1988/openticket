<?php

namespace DG\OpenticketBundle\Model\Ticket;


use DG\OpenticketBundle\Model\Ticket;
use DG\OpenticketBundle\Model\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ticket_messages")
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class Message
{
    const INTERNAL = 0;
    const EXTERNAL = 1;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\DG\OpenticketBundle\Model\Ticket")
     * @ORM\JoinColumn(name="ticket_id", nullable=false)
     *
     * @var Ticket
     */
    private $ticket;

    /**
     * @ORM\ManyToOne(targetEntity="\DG\OpenticketBundle\Model\User")
     * @ORM\JoinColumn(name="created_by", nullable=false)
     *
     * @var User
     */
    private $createdBy;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="text", type="text")
     *
     * @var string
     */
    private $text;

    /**
     * @ORM\Column(name="type", type="smallint")
     *
     * @var int
     */
    private $type;

    /**
     * @ORM\Column(name="created_time", type="datetimetz")
     *
     * @var \DateTime
     */
    private $createdTime;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->createdTime = new \DateTime();
    }

    /**
     * @return static
     */
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
     * @return Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param Ticket $ticket
     * @return Message
     */
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
        return $this;
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
     * @return Message
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Message
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;
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
     * Message::INTERNAL - for internal messages (for staff)
     * Message::EXTERNAL - for internal messages (for clients)
     *
     * @param int $type
     * @return Message
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInternal()
    {
        return $this->type === self::INTERNAL;
    }

    /**
     * @return bool
     */
    public function isExternal()
    {
        return $this->type === self::EXTERNAL;
    }
}