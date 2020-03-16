<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use MyWebsite\Entity\User;

/**
 * Interface AuthInterface.
 */
interface AuthInterface
{
    /**
     * Getter user
     *
     * @return User|null
     */
    public function getUser(): ?User;
}
