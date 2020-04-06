<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite;

use DI\ContainerBuilder;
use Exception;
use MyWebsite\Utils\Middleware\CombinedMiddleware;
use MyWebsite\Utils\Middleware\RoutePrefixedMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class App.
 */
class App implements RequestHandlerInterface
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
        }
        $this->middlewares[] = new RoutePrefixedMiddleware($this->getContainer(), $routePrefix, $middleware);

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

        return $this->handle($request);
    }

    /**
     * Call the CombinedMiddleware
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->index++;
        if ($this->index > 1) {
            throw new Exception();
        }
        $middleware = new CombinedMiddleware($this->getContainer(), $this->middlewares);

        return $middleware->process($request, $this);
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
