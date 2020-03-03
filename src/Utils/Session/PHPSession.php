<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Session;

/**
 * Class PHPSession.
 */
class PHPSession implements SessionInterface
{
    /**
     * Ensure that the Session has been started
     *
     * @return void
     */
    public function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        $this->ensureStarted();
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): void
    {
        $this->ensureStarted();
        unset($_SESSION[$key]);
    }
}
