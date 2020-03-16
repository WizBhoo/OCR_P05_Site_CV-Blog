<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Utils\RedirectResponse;
use MyWebsite\Utils\Session\FlashService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class LogoutController.
 */
class LogoutController extends AbstractController
{
    /**
     * LoginOutController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface|string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $this->auth->logout();
        (new FlashService($this->session))
            ->success('Vous êtes bien déconnecté.');

        return new RedirectResponse('/');
    }
}
