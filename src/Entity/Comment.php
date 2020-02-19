<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Entity;

use DateTime;
use Exception;

/**
 * Class Comment.
 */
class Comment
{
    /**
     * Comment's id
     *
     * @var int
     */
    protected $id;

    /**
     * Comment's content
     *
     * @var string
     */
    protected $content;

    /**
     * A DateTime Instance
     *
     * @var DateTime
     */
    protected $date;

    /**
     * Comment's status (1 = True; 0 = False)
     *
     * @var bool
     */
    protected $commentStatus;

    /**
     * Comment constructor.
     *
     * @throws Exception
     *
     * @return void
     */
    public function __construct()
    {
        if ($this->date) {
            $this->date = new DateTime($this->date);
        }
    }

    /**
     * Getter id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter id
     *
     * @param $id
     *
     * @return void
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Getter content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Setter content
     *
     * @param $content
     *
     * @return void
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * Getter date
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Setter date
     *
     * @param $date
     *
     * @return void
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * Getter status
     *
     * @return bool
     */
    public function getCommentStatus()
    {
        return $this->commentStatus;
    }

    /**
     * Setter status
     *
     * @param $commentStatus
     *
     * @return void
     */
    public function setCommentStatus($commentStatus): void
    {
        $this->commentStatus = $commentStatus;
    }
}
