<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Repository;

use Cocur\Slugify\Slugify;
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
                    DATE_FORMAT(publication_date, \'%d/%m/%Y à %H:%i\') as publishedAt,
                    DATE_FORMAT(modification_date, \'%d/%m/%Y à %H:%i\') as updatedAt,
                    CONCAT(first_name, \' \', last_name) as nameAuthor,
                    COUNT(status_comment IS TRUE OR NULL) as nbrComments
                FROM Posts
                INNER JOIN User ON Posts.user_id = User.id
                LEFT JOIN Comments on Posts.id = Comments.post_id
                GROUP BY Posts.id, publishedAt
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
                    extract,
                    content,
                    DATE_FORMAT(publication_date, \'%d/%m/%Y à %H:%i\') as publishedAt,
                    DATE_FORMAT(modification_date, \'%d/%m/%Y à %H:%i\') as updatedAt,
                    Posts.user_id,
                    CONCAT(first_name, \' \', last_name) as nameAuthor,
                    COUNT(status_comment IS TRUE OR NULL) as nbrComments
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
     * To get a list of authors
     *
     * @return array
     */
    public function findListAuthors(): array
    {
        $results = $this->pdo
            ->query(
                'SELECT id,
                    CONCAT(first_name, \' \', last_name)
                FROM User
                WHERE account_type = \'admin\''
            )
            ->fetchAll(PDO::FETCH_NUM);
        $list = [];
        foreach ($results as $result) {
            $list[$result[0]] = $result[1];
        }

        return $list;
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
        $slugify = new Slugify();
        $params['slug'] = $slugify->slugify($params['title']);
        $statement = $this->pdo->prepare(
            'INSERT INTO Posts
            SET user_id = :user_id,
                slug = :slug,
                title = :title,
                extract = :extract,
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
        $slugify = new Slugify();
        $params['newSlug'] = $slugify->slugify($params['title']);
        $statement = $this->pdo->prepare(
            'UPDATE Posts
            SET user_id = :user_id,
                slug = :newSlug,
                title = :title,
                extract = :extract,
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
