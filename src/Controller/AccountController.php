<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

/**
 * Class AccountController.
 */
class AccountController extends AbstractController
{
    /**
     * AccountController __invoke.
     *
     * @return string
     */
    public function __invoke(): string
    {
        $user = $this->auth->getUser();

        return $this->renderer->renderView(
            'account/profile',
            compact('user')
        );
    }
}
