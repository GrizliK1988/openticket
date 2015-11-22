<?php

namespace DG\OpenticketBundle\Model\Ticket\Message;


use DG\OpenticketBundle\Model\Ticket;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ticket_messages_attachments")
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class Attachment
{
    /**
     * @ORM\Column(type="integer", nullable=false)
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
     * @ORM\ManyToOne(targetEntity="\DG\OpenticketBundle\Model\Ticket\Message")
     * @ORM\JoinColumn(name="message_id", nullable=false)
     *
     * @var Ticket\Message
     */
    private $message;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $filePath;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $fileType;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $fileSize;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $deleted = false;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $expired = false;

    public static function create()
    {
        return new static;
    }

    /**
     * Attachment constructor.
     */
    public function __construct()
    {
        $this->createdTime = new \DateTime();
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
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * @return Ticket\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Ticket\Message $message
     * @return Attachment
     */
    public function setMessage(Ticket\Message $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     * @return Attachment
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param string $fileType
     * @return Attachment
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @param string $fileSize
     * @return Attachment
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
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
     * @return Attachment
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isExpired()
    {
        return $this->expired;
    }

    /**
     * @param boolean $expired
     * @return Attachment
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
        return $this;
    }
}