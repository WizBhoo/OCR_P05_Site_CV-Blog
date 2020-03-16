<?php

/**
 * (c) Adrien PIERRARD
 */
namespace MyWebsite\Utils\Auth;

/**
 * Interface UserInterface.
 */
interface UserInterface
{
    /**
     * Getter email
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Getter roles
     *
     * @return string[]
     */
    public function getRoles(): array;
}
