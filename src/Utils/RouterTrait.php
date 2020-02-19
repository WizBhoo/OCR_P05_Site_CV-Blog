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
     * A Router Instance
     *
     * @var Router
     */
    protected $router;

    /**
     * Getter router
     *
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Setter router
     *
     * @param Router $router
     *
     * @return void
     */
    public function setRouter($router): void
    {
        $this->router = $router;
    }
}
