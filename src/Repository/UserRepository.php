<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Repository;

use MyWebsite\Entity\User;
use PDO;

/**
 * Class UserRepository.
 */
class UserRepository
{
    /**
     * A PDO Instance
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * UserRepository constructor.
     *
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * To get a User from his email
     *
     * @param string $email
     *
     * @return User|null
     */
    public function findUser(string $email): ?User
    {
        $query = $this->pdo
            ->prepare(
                'SELECT User.id,
                CONCAT(first_name, \' \', last_name) as userName,
                first_name as firstName,
                last_name as lastName,
                email,
                password
                FROM User
                WHERE email = ?'
            );
        $query->execute([$email]);
        $query->setFetchMode(PDO::FETCH_CLASS, User::class);
        if (!$user = $query->fetch()) {
            return null;
        }

        return $user;
    }

    /**
     * To get a User from his id in DB
     *
     * @param int $id
     *
     * @return User|null
     */
    public function findUserById(int $id): ?User
    {
        $query = $this->pdo
            ->prepare(
                'SELECT User.id,
                CONCAT(first_name, \' \', last_name) as userName,
                first_name as firstName,
                last_name as lastName,
                email,
                password
                FROM User
                WHERE id = ?'
            );
        $query->execute([$id]);
        $query->setFetchMode(PDO::FETCH_CLASS, User::class);
        if (!$user = $query->fetch()) {
            return null;
        }

        return $user;
    }

    /**
     * To insert a User in Database
     *
     * @param array $params
     *
     * @return bool
     */
    public function insertUser(array $params): bool
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO User
            SET first_name = :firstName,
                last_name = :lastName,
                email = :email,
                password = :password'
        );

        return $statement->execute($params);
    }

    /**
     * To update a User in Database
     *
     * @param int   $id
     * @param array $params
     *
     * @return bool
     */
    public function updateUser(int $id, array $params): bool
    {
        $params['id'] = $id;
        $statement = $this->pdo->prepare(
            'UPDATE User
            SET first_name = :firstName,
                last_name = :lastName,
                password = :password
            WHERE User.id = :id'
        );

        return $statement->execute($params);
    }
}
