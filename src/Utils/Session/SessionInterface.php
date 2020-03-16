<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Session;

/**
 * Interface SessionInterface.
 */
interface SessionInterface
{
    /**
     * Retrieve an information in Session
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Add an information in Session
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * Delete an information in Session
     *
     * @param string $key
     *
     * @return void
     */
    public function delete(string $key): void;
}
