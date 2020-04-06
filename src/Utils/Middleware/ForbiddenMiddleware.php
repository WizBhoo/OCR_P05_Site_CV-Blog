<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use MyWebsite\Utils\Exception\ForbiddenException;
use MyWebsite\Utils\Exception\NotConnectedException;
use MyWebsite\Utils\RedirectResponse;
use MyWebsite\Utils\Session\FlashService;
use MyWebsite\Utils\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ForbiddenMiddleware.
 */
class ForbiddenMiddleware implements MiddlewareInterface
{
    /**
     * Path to login
     *
     * @var string
     */
    protected $loginPath;

    /**
     * A SessionInterface Injection
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * ForbiddenMiddleware constructor.
     *
     * @param string           $loginPath
     * @param SessionInterface $session
     */
    public function __construct(string $loginPath, SessionInterface $session)
    {
        $this->loginPath = $loginPath;
        $this->session = $session;
    }


    /**
     * Redirect not connected user to login path
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ForbiddenException $exception) {
            $this->session->set('auth.redirect', $request->getUri()->getPath());
            (new FlashService($this->session))->error(
                'Vous devez être Administrateur pour accéder à cette page'
            );

            return new RedirectResponse($this->loginPath);
        } catch (NotConnectedException $exception) {
            $this->session->set('auth.redirect', $request->getUri()->getPath());
            (new FlashService($this->session))->error(
                'Vous devez être connecté pour accéder à cette page'
            );

            return new RedirectResponse($this->loginPath);
        }
    }
}
