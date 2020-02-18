<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

/**
 * Trait RouterAware
 */
trait RouterTrait
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router): void
    {
        $this->router = $router;
    }
}
