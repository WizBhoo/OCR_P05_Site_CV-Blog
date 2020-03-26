<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Repository;

use MyWebsite\Entity\User;
use PDO;
use Ramsey\Uuid\Uuid;

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
     * To get all Users
     *
     * @return User[]
     */
    public function findAllUser(): array
    {
        $query = $this->pdo
            ->query(
                'SELECT User.id,
                CONCAT(first_name, \' \', last_name) as userName,
                email,
                account_type as accountType,
                account_status as accountStatus
                FROM User
                ORDER BY id DESC'
            );
        $query->setFetchMode(PDO::FETCH_CLASS, User::class);

        return $query->fetchAll();
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
                password,
                password_reset as passwordReset,
                password_reset_at as passwordResetAt,
                account_type as accountType,
                account_status as accountStatus
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
                password,
                password_reset as passwordReset,
                password_reset_at as passwordResetAt,
                account_type as accountType,
                account_status as accountStatus
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

    /**
     * To activate User account with appropriate granted access in Database
     *
     * @param int $id
     *
     * @return bool
     */
    public function activateUser(int $id): bool
    {
        $params['id'] = $id;
        $statement = $this->pdo->prepare(
            'UPDATE User
            SET account_status = 1
            WHERE User.id = :id'
        );

        return $statement->execute($params);
    }

    /**
     * To switch User account type in Database
     *
     * @param array $params
     *
     * @return bool
     */
    public function switchAccountType(array $params): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE User
            SET account_type = :accountType
            WHERE User.id = :id'
        );

        return $statement->execute($params);
    }

    /**
     * To delete a User in Database
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM User WHERE id = ?');

        return $statement->execute([$id]);
    }

    /**
     * Generate a token in db for a User to reset password
     *
     * @param int $id
     *
     * @return string
     */
    public function resetPassword(int $id): string
    {
        $token = Uuid::uuid4()->toString();
        $params['id'] = $id;
        $params['token'] = $token;
        $statement = $this->pdo->prepare(
            'UPDATE User
            SET password_reset = :token,
                password_reset_at = now()
            WHERE User.id = :id'
        );
        $statement->execute($params);

        return $token;
    }

    /**
     * @param int    $id
     * @param string $password
     *
     * @return void
     */
    public function updatePassword(int $id, string $password): void
    {
        $params['id'] = $id;
        $params['password'] = password_hash(
            $password,
            PASSWORD_DEFAULT
        );
        $statement = $this->pdo->prepare(
            'UPDATE User
            SET password = :password,
                password_reset = null,
                password_reset_at = null
            WHERE User.id = :id'
        );
        $statement->execute($params);
    }
}
