<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AdminController.
 */
class AdminController extends AbstractController
{
    /**
     * AdminController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        if (!is_null($request)) {
            return $this->dashboard();
        }

        return $this->error404();
    }

    /**
     * Route callable function dashboard.
     *
     * @return string
     */
    public function dashboard(): string
    {
        return $this->renderer->renderView('admin/dashboard');
    }
}
