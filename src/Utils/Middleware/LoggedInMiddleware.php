<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use MyWebsite\Utils\AuthInterface;
use MyWebsite\Utils\Exception\NotConnectedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
     * Verify if a User is logged or not to allow access or not to site parts
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     *
     * @throws NotConnectedException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->auth->getUser();
        if (is_null($user)) {
            throw new NotConnectedException();
        }

        return $handler->handle($request->withAttribute('user', $user));
    }
}
