<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use MyWebsite\Utils\Route;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $route = $request->getAttribute(Route::class);
        if (is_null($route)) {
            return $delegate->process($request);
        }
        $callback = $route->getCallback();
        if (!is_array($callback)) {
            $callback = [$callback];
        }

        return (new CombinedMiddleware($this->container, $callback))
            ->process($request, $delegate);
    }
}
