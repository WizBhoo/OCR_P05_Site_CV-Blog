<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class CombinedMiddlewareDelegate.
 */
class CombinedMiddlewareDelegate implements RequestHandlerInterface
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
     * @var RequestHandlerInterface
     */
    protected $handler;

    /**
     * CombinedMiddlewareDelegate constructor.
     *
     * @param ContainerInterface      $container
     * @param array                   $middlewares
     * @param RequestHandlerInterface $handler
     */
    public function __construct(ContainerInterface $container, array $middlewares, RequestHandlerInterface $handler)
    {
        $this->container = $container;
        $this->middlewares = $middlewares;
        $this->handler = $handler;
    }

    /**
     * Call Middlewares as callable or object and return appropriate Response
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if (!is_null($middleware) && is_callable($middleware)) {
            $response = call_user_func_array(
                $middleware,
                [$request, [$this, 'handle']]
            );
            if (is_string($response)) {
                return new Response(200, [], $response);
            }

            return $response;
        }
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }

        return $this->handler->handle($request);
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
