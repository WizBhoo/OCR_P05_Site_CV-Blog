<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Utils\RedirectResponse;
use MyWebsite\Utils\Session\FlashService;
use Psr\Http\Message\ResponseInterface;

/**
 * Class LogoutController.
 */
class LogoutController extends AbstractController
{
    /**
     * LoginOutController __invoke.
     *
     * @return ResponseInterface|string
     */
    public function __invoke()
    {
        $this->auth->logout();
        (new FlashService($this->session))
            ->success('Vous êtes bien déconnecté.');

        return new RedirectResponse('/');
    }
}
