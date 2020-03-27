<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use MyWebsite\Utils\Middleware\AccountTypeMiddleware;

/**
 * Class AccountTypeMiddlewareFactory.
 */
class AccountTypeMiddlewareFactory
{
    /**
     * An AuthInterface Injection
     *
     * @var AuthInterface
     */
    protected $auth;

    /**
     * AccountTypeMiddlewareFactory constructor.
     *
     * @param AuthInterface $auth
     */
    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Return an AccountTypeMiddleware Instance
     *
     * @param $accountType
     *
     * @return AccountTypeMiddleware
     */
    public function makeForAccountType($accountType): AccountTypeMiddleware
    {
        return new AccountTypeMiddleware($this->auth, $accountType);
    }
}
