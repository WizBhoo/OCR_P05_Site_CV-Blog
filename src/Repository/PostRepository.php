<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Repository;

use MyWebsite\Entity\Post;
use PDO;

/**
 * Class Post.
 */
class PostRepository
{
    /**
     * A PDO Instance
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * PostRepository constructor.
     *
     * @param PDO $pdo
     *
     * @return void
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * To get all BlogPosts
     *
     * @return Post[]
     */
    public function getAll(): array
    {
        return $this->pdo
            ->query(
                'SELECT Posts.id, 
                    slug, 
                    title, 
                    resume, 
                    DATE_FORMAT(publication_date, \'le %d/%m/%Y à %h:%m\') as publi_date,
                    DATE_FORMAT(modification_date, \'le %d/%m/%Y à %h:%m\') as modif_date,
                    last_name,
                    first_name
                FROM Posts
                INNER JOIN User ON Posts.user_id = User.id
                ORDER BY publication_date DESC'
            )
            ->fetchAll()
        ;
    }

    /**
     * To get a BlogPost from his slug
     *
     * @param string $slug
     *
     * @return Post|null
     */
    public function findPost(string $slug): ?Post
    {
        $query = $this->pdo
            ->prepare(
                'SELECT Posts.id, 
                    slug, 
                    title, 
                    content,
                    DATE_FORMAT(publication_date, \'le %d/%m/%Y à %h:%m\') as publi_date,
                    DATE_FORMAT(modification_date, \'le %d/%m/%Y à %h:%m\') as modif_date,
                    last_name,
                    first_name
                FROM Posts
                INNER JOIN User ON Posts.user_id = User.id
                WHERE Posts.slug = ?'
            )
        ;
        $query->execute([$slug]);
        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);
        if (!$post = $query->fetch()) {
            return null;
        }

        return $post;
    }
}
