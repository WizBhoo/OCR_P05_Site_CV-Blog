<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Entity;

use DateTime;
use Exception;

/**
 * Class Post.
 */
class Post
{
    /**
     * Post's id
     *
     * @var int
     */
    protected $id;

    /**
     * Post's slug
     *
     * @var string
     */
    protected $slug;

    /**
     * Post's title
     *
     * @var string
     */
    protected $title;

    /**
     * Post's extract
     *
     * @var string
     */
    protected $extract;

    /**
     * Post's content
     *
     * @var string
     */
    protected $content;

    /**
     * A DateTime Instance
     *
     * @var DateTime
     */
    protected $publishedAt;

    /**
     * A DateTime Instance
     *
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * Post constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        if ($this->publishedAt) {
            $this->publishedAt = new DateTime($this->publishedAt);
        }
        if ($this->updatedAt) {
            $this->updatedAt = new DateTime($this->updatedAt);
        }
    }

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
     * Getter slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Setter slug
     *
     * @param string $slug
     *
     * @return void
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Getter title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Setter title
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter extract
     *
     * @return string
     */
    public function getExtract(): string
    {
        return $this->extract;
    }

    /**
     * Setter extract
     *
     * @param string $extract
     *
     * @return void
     */
    public function setExtract(string $extract): void
    {
        $this->extract = $extract;
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
     * Getter publishedAt
     *
     * @return DateTime
     */
    public function getPublishedAt(): DateTime
    {
        return $this->publishedAt;
    }

    /**
     * Setter publishedAt
     *
     * @param DateTime $publishedAt
     *
     * @return void
     */
    public function setPublishedAt(DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * Getter updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Setter updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
