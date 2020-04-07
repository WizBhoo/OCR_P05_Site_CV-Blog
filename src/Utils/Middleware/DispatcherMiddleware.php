<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use MyWebsite\Utils\Route;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class DispatcherMiddleware.
 */
class DispatcherMiddleware implements MiddlewareInterface
{
    /**
     * A ContainerInterface Injection
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DispatcherMiddleware constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Retrieve Route & Callback and return appropriate Response
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(Route::class);
        if (null === $route) {
            return $handler->handle($request);
        }
        $callback = $route->getCallback();
        if (!is_array($callback)) {
            $callback = [$callback];
        }

        return (new CombinedMiddleware($this->container, $callback))
            ->process($request, $handler);
    }
}
