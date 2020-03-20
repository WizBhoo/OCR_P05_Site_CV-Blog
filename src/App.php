<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite;

use DI\ContainerBuilder;
use Exception;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use MyWebsite\Utils\Middleware\RoutePrefixedMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class App.
 */
class App implements DelegateInterface
{
    /**
     * A ContainerInterface instance.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Container config definition
     *
     * @var string|array|null
     */
    protected $definition;

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
     * App constructor.
     *
     * @param string|array|null $definition
     */
    public function __construct($definition = null)
    {
        $this->definition = $definition;
    }

    /**
     * Add a middleware
     *
     * @param string|callable|MiddlewareInterface      $routePrefix
     * @param string|null|callable|MiddlewareInterface $middleware
     *
     * @return App
     */
    public function pipe($routePrefix, $middleware = null): self
    {
        if (null === $middleware) {
            $this->middlewares[] = $routePrefix;
        } else {
            $this->middlewares[] = new RoutePrefixedMiddleware($this->getContainer(), $routePrefix, $middleware);
        }

        return $this;
    }

    /**
     * App run
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $this->container = $this->getContainer();

        return $this->process($request);
    }

    /**
     * Call a Middleware as callable or object
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function process(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if (!is_null($middleware) && is_callable($middleware)) {
            return call_user_func_array($middleware, [$request, [$this, 'process']]);
        }
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }

        throw new Exception('No middleware intercepted this request');
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
                $middleware = $this->container->get($this->middlewares[$this->index]);
            } else {
                $middleware = $this->middlewares[$this->index];
            }
            $this->index++;

            return $middleware;
        }

        return null;
    }

    /**
     * Getter Container
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $builder = new ContainerBuilder();
            if ($this->definition) {
                $builder->addDefinitions($this->definition);
            }
            try {
                $this->container = $builder->build();
            } catch (Exception $e) {
                $e->getMessage();
            }
        }

        return $this->container;
    }
}
