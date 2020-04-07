<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class CombinedMiddleware.
 */
class CombinedMiddleware implements MiddlewareInterface
{
    /**
     * A ContainerInterface Injection
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * A table that contains Middlewares
     *
     * @var array
     */
    protected $middlewares;

    /**
     * CombinedMiddleware constructor.
     *
     * @param ContainerInterface $container
     * @param array              $middlewares
     */
    public function __construct(ContainerInterface $container, array $middlewares)
    {
        $this->container = $container;
        $this->middlewares = $middlewares;
    }

    /**
     * Allow to call multiple middleware and return appropriate Response
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $handler = new CombinedMiddlewareDelegate(
            $this->container,
            $this->middlewares,
            $handler
        );

        return $handler->handle($request);
    }
}
