<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class LoginController.
 */
class LoginController extends AbstractController
{
    /**
     * LoginController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface|string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->renderer->renderView('auth/login');
    }
}
