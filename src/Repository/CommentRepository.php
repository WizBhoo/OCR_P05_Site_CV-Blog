<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Repository;

use MyWebsite\Entity\Comment;
use PDO;

/**
 * Class CommentRepository.
 */
class CommentRepository
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
     * To get all Comments link to a BlogPost
     *
     * @param string $slug
     *
     * @return Comment[]|null
     */
    public function findComments(string $slug): ?array
    {
        $query = $this->pdo
            ->prepare(
                'SELECT Comments.id,
                    slug,
                    content_comment as content, 
                    DATE_FORMAT(date_comment, \'%d/%m/%Y à %H:%i\') as commentedAt,
                    status_comment as commentStatus,
                    CONCAT(first_name, \' \', last_name) as nameAuthor
                FROM Comments
                INNER JOIN Posts ON Comments.post_id = Posts.id
                INNER JOIN User ON Comments.user_id = User.id
                WHERE slug = ?
                ORDER BY commentedAt'
            );
        $query->execute([$slug]);
        $query->setFetchMode(PDO::FETCH_CLASS, Comment::class);
        if (!$comments = $query->fetchAll()) {
            return null;
        }

        return $comments;
    }

    /**
     * To get all Comments
     *
     * @return Comment[]
     */
    public function findAllComment(): array
    {
        $query = $this->pdo
            ->query(
                'SELECT Comments.id,
                content_comment as content,
                DATE_FORMAT(date_comment, \'%d/%m/%Y à %H:%i\') as commentedAt,
                CONCAT(first_name, \' \', last_name) as commentBy,
                title as onArticle,
                status_comment as commentStatus
                FROM Comments
                INNER JOIN User ON Comments.user_id = User.id
                INNER JOIN Posts on Comments.post_id = Posts.id
                ORDER BY commentedAt DESC, onArticle'
            );
        $query->setFetchMode(PDO::FETCH_CLASS, Comment::class);

        return $query->fetchAll();
    }

    /**
     * To insert a Comment in Database
     *
     * @param array $params
     *
     * @return bool
     */
    public function insertComment(array $params): bool
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO Comments
            SET post_id = :id,
                user_id = 1,
                content_comment = :content,
                status_comment = 0'
        );

        return $statement->execute($params);
    }

    /**
     * To update Comment's status in Database
     *
     * @param int $id
     *
     * @return bool
     */
    public function updateComment(int $id): bool
    {
        $params['id'] = $id;
        $statement = $this->pdo->prepare(
            'UPDATE Comments
            SET status_comment = 1
            WHERE Comments.id = :id'
        );

        return $statement->execute($params);
    }

    /**
     * To delete a Comment in Database
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteComment(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM Comments WHERE id = ?');

        return $statement->execute([$id]);
    }
}
