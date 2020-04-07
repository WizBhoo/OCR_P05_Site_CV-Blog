<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

/**
 * Class Route. To represent a matched route
 */
class Route
{
    /**
     * Route name
     *
     * @var string
     */
    protected $name;

    /**
     * Route associated callback.
     *
     * @var string|callable
     */
    protected $callback;

    /**
     * Route associated URL parameters.
     *
     * @var array
     */
    protected $parameters;

    /**
     * Route constructor.
     *
     * @param string          $name
     * @param string|callable $callback
     * @param array           $parameters
     */
    public function __construct(string $name, $callback, array $parameters)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->parameters = $parameters;
    }

    /**
     * Route getName.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Route getCallback.
     *
     * @return string|callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Route getParams. To retrieve URL parameters
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->parameters;
    }
}
