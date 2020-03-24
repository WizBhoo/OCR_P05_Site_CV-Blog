<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CombinedMiddlewareDelegate.
 */
class CombinedMiddlewareDelegate implements DelegateInterface
{
    /**
     * A table that contains Middlewares
     *
     * @var string[]
     */
    protected $middlewares = [];

    /**
     * A Middlewares table index
     *
     * @var int
     */
    protected $index = 0;

    /**
     * A ContainerInterface Injection
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * A DelegateInterface Injection
     *
     * @var DelegateInterface
     */
    protected $delegate;

    /**
     * CombinedMiddlewareDelegate constructor.
     *
     * @param ContainerInterface $container
     * @param array              $middlewares
     * @param DelegateInterface  $delegate
     */
    public function __construct(ContainerInterface $container, array $middlewares, DelegateInterface $delegate)
    {
        $this->container = $container;
        $this->middlewares = $middlewares;
        $this->delegate = $delegate;
    }

    /**
     * Call Middlewares as callable or object and return appropriate Response
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if (!is_null($middleware) && is_callable($middleware)) {
            $response = call_user_func_array(
                $middleware,
                [$request, [$this, 'process']]
            );
            if (is_string($response)) {
                return new Response(200, [], $response);
            }

            return $response;
        }
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }

        return $this->delegate->process($request);
    }

    /**
     * Getter Middleware
     *
     * @return mixed|null
     */
    public function getMiddleware()
    {
        if (array_key_exists($this->index, $this->middlewares)) {
            if (is_string($this->middlewares[$this->index])) {
                $middleware = $this->container->get(
                    $this->middlewares[$this->index]
                );
            } else {
                $middleware = $this->middlewares[$this->index];
            }
            $this->index++;

            return $middleware;
        }

        return null;
    }
}
