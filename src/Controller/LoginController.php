<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Psr\Http\Message\ResponseInterface;

/**
 * Class LoginController.
 */
class LoginController extends AbstractController
{
    /**
     * LoginController __invoke.
     *
     * @return ResponseInterface|string
     */
    public function __invoke()
    {
        return $this->renderer->renderView('auth/login');
    }
}
