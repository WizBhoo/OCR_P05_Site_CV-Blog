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
                    date_comment as date,
                    status_comment as commentStatus,
                    CONCAT(first_name, \' \', last_name) as nameAuthor
                FROM Comments
                INNER JOIN Posts ON Comments.post_id = Posts.id
                INNER JOIN User ON Comments.user_id = User.id
                WHERE slug = ?
                ORDER BY date'
            );
        $query->execute([$slug]);
        $query->setFetchMode(PDO::FETCH_CLASS, Comment::class);
        if (!$comments = $query->fetchAll()) {
            return null;
        }

        return $comments;
    }
}
