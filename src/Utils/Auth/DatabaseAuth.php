<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Auth;

use Exception;
use MyWebsite\Entity\User;
use MyWebsite\Repository\UserRepository;
use MyWebsite\Utils\AuthInterface;
use MyWebsite\Utils\Session\SessionInterface;

/**
 * Class DatabaseAuth.
 */
class DatabaseAuth implements AuthInterface
{
    /**
     * A UserRepository Instance
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * A SessionInterface Injection
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * A User Instance
     *
     * @var User
     */
    protected $user;

    /**
     * DatabaseAuth constructor.
     *
     * @param UserRepository   $userRepository
     * @param SessionInterface $session
     */
    public function __construct(UserRepository $userRepository, SessionInterface $session)
    {
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    /**
     * Check if User exists and can be logged in
     *
     * @param string|null $email
     * @param string|null $password
     *
     * @return User|null
     */
    public function login(string $email, string $password): ?User
    {
        if (empty($email) || empty($password)) {
            return null;
        }
        $user = $this->userRepository->findUser($email);
        if ($user && password_verify($password, $user->getPassword())) {
            if (true === $user->getAccountStatus()) {
                $this->setUser($user);

                return $user;
            }

            return $user;
        }

        return null;
    }

    /**
     * Check if User exists and can be logged in
     *
     * @return void
     */
    public function logout(): void
    {
        $this->session->delete('auth.user');
    }

    /**
     * Getter user
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->user) {
            return $this->user;
        }
        $userId = $this->session->get('auth.user');
        if ($userId) {
            try {
                $this->user = $this->userRepository->findUserById($userId);

                return $this->user;
            } catch (Exception $exception) {
                $this->session->delete('auth.user');

                return null;
            }

        }

        return null;
    }

    /**
     * Setter user
     *
     * @param User $user
     *
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->session->set('auth.user', $user->getId());
        $this->user = $user;
    }
}
