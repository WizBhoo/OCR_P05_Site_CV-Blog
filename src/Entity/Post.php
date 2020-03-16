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
     * Post's image
     *
     * @var string
     */
    protected $image;

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
     * @return string|null
     */
    public function getSlug(): ?string
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
     * @return string|null
     */
    public function getTitle(): ?string
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
     * @return string|null
     */
    public function getExtract(): ?string
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
     * @return string|null
     */
    public function getContent(): ?string
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
     * @return string|DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Setter publishedAt
     *
     * @param $datetime
     *
     * @return void
     *
     * @throws Exception
     */
    public function setPublishedAt($datetime): void
    {
        if (is_string($datetime)) {
            $this->publishedAt = new DateTime($datetime);
        }
    }

    /**
     * Getter updatedAt
     *
     * @return string|DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Setter updatedAt
     *
     * @param $datetime
     *
     * @return void
     *
     * @throws Exception
     */
    public function setUpdatedAt($datetime): void
    {
        if (is_string($datetime)) {
            $this->updatedAt = new DateTime($datetime);
        }
    }

    /**
     * Getter image
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Setter image
     *
     * @param string $image
     *
     * @return void
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * Get a thumbnail image
     *
     * @return string
     */
    public function getThumb(): string
    {
        ['filename' => $filename, 'extension' => $extension] = pathinfo($this->image);

        return sprintf("/img/blog/%s_thumb.%s", $filename, $extension);
    }

    /**
     * Get original image
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        return sprintf("/img/blog/%s", $this->image);
    }
}
