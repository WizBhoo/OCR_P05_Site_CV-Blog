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
     * @var callable
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
     * @param string   $name
     * @param callable $callback
     * @param array    $parameters
     */
    public function __construct(string $name, callable $callback, array $parameters)
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
     * @return callable
     */
    public function getCallback(): callable
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
