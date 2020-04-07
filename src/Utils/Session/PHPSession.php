<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Session;

use ArrayAccess;

/**
 * Class PHPSession.
 */
class PHPSession implements SessionInterface, ArrayAccess
{
    /**
     * Ensure that the Session has been started
     *
     * @return void
     */
    public function ensureStarted(): void
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

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        $this->ensureStarted();

        return array_key_exists($offset, $_SESSION);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }
}
