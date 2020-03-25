<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Entity;

use MyWebsite\Utils\Auth\UserInterface;

/**
 * Class User.
 */
class User implements UserInterface
{
    /**
     * User's id
     *
     * @var int
     */
    protected $id;

    /**
     * User's last_name
     *
     * @var string
     */
    protected $lastName;

    /**
     * User's first_name
     *
     * @var string
     */
    protected $firstName;

    /**
     * User's email
     *
     * @var string
     */
    protected $email;

    /**
     * User's password
     *
     * @var string
     */
    protected $password;

    /**
     * User account type
     *
     * @var string
     */
    protected $accountType;

    /**
     * User account status (1 = True; 0 = False)
     *
     * @var bool
     */
    protected $accountStatus;

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
     * Getter first_name
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Setter first_name
     *
     * @param string $firstName
     *
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Getter last_name
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Setter last_name
     *
     * @param string $lastName
     *
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Getter email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Setter email
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Setter password
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Getter account type
     *
     * @return string
     */
    public function getAccountType(): string
    {
        return $this->accountType;
    }

    /**
     * Setter account type
     *
     * @param string $accountType
     *
     * @return void
     */
    public function setAccountType(string $accountType): void
    {
        $this->accountType = $accountType;
    }

    /**
     * Getter account status
     *
     * @return bool
     */
    public function getAccountStatus(): bool
    {
        return $this->accountStatus;
    }

    /**
     * Setter account status
     *
     * @param bool $accountStatus
     *
     * @return void
     */
    public function setAccountStatus(bool $accountStatus): void
    {
        $this->accountStatus = $accountStatus;
    }
}
