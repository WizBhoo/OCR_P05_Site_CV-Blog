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
                    COUNT(post_id) as nbrComments
                FROM Posts
                INNER JOIN User ON Posts.user_id = User.id
                LEFT JOIN Comments on Posts.id = Comments.post_id
                GROUP BY Posts.id, publiDate
                ORDER BY publiDate DESC'
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
                    resume,
                    content,
                    publication_date as publishedAt,
                    modification_date as updatedAt,
                    CONCAT(first_name, \' \', last_name) as nameAuthor,
                    COUNT(Comments.post_id) as nbrComments
                FROM Posts
                INNER JOIN User ON Posts.user_id = User.id
                LEFT JOIN Comments on Posts.id = Comments.post_id
                WHERE slug = ?
                GROUP BY Posts.id'
            );
        $query->execute([$slug]);
        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);
        if (!$post = $query->fetch()) {
            return null;
        }

        return $post;
    }

    /**
     * To insert a BlogPost in Database
     *
     * @param array $params
     *
     * @return bool
     */
    public function insertPost(array $params): bool
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO Posts
            SET user_id = :user_id,
                slug = :slug,
                title = :title,
                resume = :resume,
                content = :content'
        );

        return $statement->execute($params);
    }

    /**
     * To update a BlogPost in Database
     *
     * @param string $slug
     * @param array  $params
     *
     * @return bool
     */
    public function updatePost(string $slug, array $params): bool
    {
        $params['slug'] = $slug;
        $statement = $this->pdo->prepare(
            'UPDATE Posts
            SET slug = :slug,
                title = :title,
                resume = :resume,
                content = :content,
                modification_date = now()
            WHERE Posts.slug = :slug'
        );

        return $statement->execute($params);
    }

    /**
     * To delete a BlogPost in Database
     *
     * @param string $slug
     *
     * @return bool
     */
    public function deletePost(string $slug): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM Posts WHERE slug = ?');

        return $statement->execute([$slug]);
    }
}
