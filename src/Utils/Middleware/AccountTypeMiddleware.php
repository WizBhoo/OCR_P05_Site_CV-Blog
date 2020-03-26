<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use MyWebsite\Utils\AuthInterface;
use MyWebsite\Utils\Exception\ForbiddenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AccountTypeMiddleware.
 */
class AccountTypeMiddleware implements MiddlewareInterface
{
    /**
     * An AuthInterface Injection
     *
     * @var AuthInterface
     */
    protected $auth;

    /**
     * An account type (user or admin)
     *
     * @var string
     */
    protected $accountType;

    /**
     * AccountTypeMiddleware constructor.
     *
     * @param AuthInterface $auth
     * @param string        $accountType
     */
    public function __construct(AuthInterface $auth, string $accountType)
    {
        $this->auth = $auth;
        $this->accountType = $accountType;
    }

    /**
     * Verify if user has granted access to admin part
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     *
     * @throws ForbiddenException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $user = $this->auth->getUser();
        if (null === $user || $this->accountType !== $user->getAccountType()) {
            throw new ForbiddenException();
        }

        return $delegate->process($request);
    }
}
