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
        $query = $this->pdo
            ->query(
                'SELECT Posts.id, 
                    slug, 
                    title, 
                    extract, 
                    publication_date as publishedAt,
                    modification_date as updatedAt,
                    CONCAT(first_name, \' \', last_name) as nameAuthor,
                    COUNT(Comments.post_id)  as nbrComments
                FROM Posts
                INNER JOIN User ON Posts.user_id = User.id
                INNER JOIN Comments on Posts.id = Comments.post_id
                GROUP BY post_id, publishedAt
                ORDER BY publishedAt DESC'
            );
        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);

        return $query->fetchAll();
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
                    publication_date as publishedAt,
                    modification_date as updatedAt,
                    CONCAT(first_name, \' \', last_name) as nameAuthor,
                    COUNT(Comments.post_id) as nbrComments
                FROM Posts
                INNER JOIN User ON Posts.user_id = User.id
                INNER JOIN Comments on Posts.id = Comments.post_id
                WHERE slug = ?
                GROUP BY post_id'
            );
        $query->execute([$slug]);
        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);
        if (!$post = $query->fetch()) {
            return null;
        }

        return $post;
    }
}
