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
    protected $commentedAt;

    /**
     * Comment's status (1 = True; 0 = False)
     *
     * @var bool
     */
    protected $commentStatus;

    /**
     * Getter id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Setter id
     *
     * @param int $id
     *
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Getter content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Setter content
     *
     * @param string $content
     *
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Getter commentedAt
     *
     * @return string|DateTime
     */
    public function getCommentedAt()
    {
        return $this->commentedAt;
    }

    /**
     * Setter commentedAt
     *
     * @param $datetime
     *
     * @return DateTime
     *
     * @throws Exception
     */
    public function setCommentedAt($datetime): DateTime
    {
        if (is_string($datetime)) {
            return $this->commentedAt = new DateTime($datetime);
        }

        return $this->commentedAt = $datetime;
    }

    /**
     * Getter status
     *
     * @return bool
     */
    public function getCommentStatus(): bool
    {
        return $this->commentStatus;
    }

    /**
     * Setter status
     *
     * @param bool $commentStatus
     *
     * @return void
     */
    public function setCommentStatus(bool $commentStatus): void
    {
        $this->commentStatus = $commentStatus;
    }
}
