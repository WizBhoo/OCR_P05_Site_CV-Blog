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
     * Post's resume
     *
     * @var string
     */
    protected $resume;

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
    protected $publiDate;

    /**
     * A DateTime Instance
     *
     * @var DateTime
     */
    protected $modifDate;

    /**
     * Post constructor.
     *
     * @throws Exception
     *
     * @return void
     */
    public function __construct()
    {
        if ($this->publiDate) {
            $this->publiDate = new DateTime($this->publiDate);
        }
        if ($this->modifDate) {
            $this->modifDate = new DateTime($this->modifDate);
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
    public function getSlug()
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
    public function getTitle()
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
     * Getter resume
     *
     * @return string
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Setter resume
     *
     * @param string $resume
     *
     * @return void
     */
    public function setResume(string $resume): void
    {
        $this->resume = $resume;
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
     * @param string $content
     *
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Getter publiDate
     *
     * @return DateTime
     */
    public function getPubliDate()
    {
        return $this->publiDate;
    }

    /**
     * Setter publiDate
     *
     * @param DateTime $publiDate
     *
     * @return void
     */
    public function setPubliDate(DateTime $publiDate): void
    {
        $this->publiDate = $publiDate;
    }

    /**
     * Getter modifDate
     *
     * @return DateTime
     */
    public function getModifDate()
    {
        return $this->modifDate;
    }

    /**
     * Setter modifDate
     *
     * @param DateTime $modifDate
     *
     * @return void
     */
    public function setModifDate(DateTime $modifDate): void
    {
        $this->modifDate = $modifDate;
    }
}
