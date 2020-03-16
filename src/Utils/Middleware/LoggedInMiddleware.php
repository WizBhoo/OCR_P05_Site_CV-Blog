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
 * Class LoggedInMiddleware.
 */
class LoggedInMiddleware implements MiddlewareInterface
{
    /**
     * An AuthInterface Injection
     *
     * @var AuthInterface
     */
    protected $auth;

    /**
     * LoggedInMiddleware constructor.
     *
     * @param AuthInterface $auth
     */
    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Verify if a User is logged or not
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
        if (is_null($user)) {
            throw new ForbiddenException();
        }

        return $delegate->process($request->withAttribute('user', $user));
    }
}
