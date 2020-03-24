<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RoutePrefixedMiddleware.
 */
class RoutePrefixedMiddleware implements MiddlewareInterface
{
    /**
     * A ContainerInterface Injection
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * A route prefix
     *
     * @var string
     */
    protected $prefix;

    /**
     * A middleware
     *
     * @var string|MiddlewareInterface
     */
    protected $middleware;

    /**
     * RoutePrefixedMiddleware constructor.
     *
     * @param ContainerInterface         $container
     * @param string                     $prefix
     * @param string|MiddlewareInterface $middleware
     */
    public function __construct(ContainerInterface $container, string $prefix, $middleware)
    {
        $this->container = $container;
        $this->prefix = $prefix;
        $this->middleware = $middleware;
    }

    /**
     * Verify if a request begin by a prefix
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (strpos($path, $this->prefix) === 0) {
            if (is_string($this->middleware)) {
                return $this->container
                    ->get($this->middleware)
                    ->process($request, $delegate);
            }

            return $this->middleware->process($request, $delegate);
        }

        return $delegate->process($request);
    }
}
