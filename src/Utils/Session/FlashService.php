<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Session;

/**
 * Class FlashService.
 */
class FlashService
{
    /**
     * A SessionInterface Instance
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * Flash's messages
     *
     * @var array|null
     */
    protected $messages;

    /**
     * Constant SESSION_KEY
     *
     * @var string
     */
    const SESSION_KEY = 'flash';

    /**
     * FlashService constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Show flash success message
     *
     * @param string $message
     *
     * @return void
     */
    public function success(string $message): void
    {
        $flash = $this->session->get(self::SESSION_KEY, []);
        $flash['success'] = $message;
        $this->session->set(self::SESSION_KEY, $flash);
    }

    /**
     * Show flash success message
     *
     * @param string $message
     *
     * @return void
     */
    public function commentSuccess(string $message): void
    {
        $flash = $this->session->get(self::SESSION_KEY, []);
        $flash['commentSuccess'] = $message;
        $this->session->set(self::SESSION_KEY, $flash);
    }

    /**
     * Show flash error message
     *
     * @param string $message
     *
     * @return void
     */
    public function error(string $message): void
    {
        $flash = $this->session->get(self::SESSION_KEY, []);
        $flash['error'] = $message;
        $this->session->set(self::SESSION_KEY, $flash);
    }

    /**
     * Retrieve message in session
     *
     * @param string $type
     *
     * @return string|null
     */
    public function get(string $type): ?string
    {
        if (is_null($this->messages)) {
            $this->messages = $this->session->get(self::SESSION_KEY, []);
            $this->session->delete(self::SESSION_KEY);
        }
        if (array_key_exists($type, $this->messages)) {
            return $this->messages[$type];
        }

        return null;
    }
}
