<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use MyWebsite\Utils\Exception\ForbiddenException;
use MyWebsite\Utils\Exception\NotConnectedException;
use MyWebsite\Utils\RedirectResponse;
use MyWebsite\Utils\Session\FlashService;
use MyWebsite\Utils\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        try {
            return $delegate->process($request);
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
