<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AccountController.
 */
class AccountController extends AbstractController
{
    /**
     * AccountController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function __invoke(ServerRequestInterface $request): string
    {
        $user = $this->auth->getUser();

        return $this->renderer->renderView(
            'account/profile',
            compact('user')
        );
    }
}
