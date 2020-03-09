<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite;

use DI\ContainerBuilder;
use Exception;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
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
     * @var string
     */
    protected $definition;

    /**
     * A table that contains Middlewares
     *
     * @var string[]
     */
    protected $middlewares;

    /**
     * A Middlewares table index
     *
     * @var int
     */
    protected $index = 0;

    /**
     * App constructor.
     *
     * @param string $definition
     */
    public function __construct(string $definition)
    {
        $this->definition = $definition;
    }

    /**
     * Add a middleware
     *
     * @param string $middleware
     *
     * @return $this
     */
    public function pipe(string $middleware): self
    {
        $this->middlewares[] = $middleware;

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

        throw new Exception('Aucun middleware n\'a intercepté cette requête');
    }

    /**
     * Getter Middleware
     *
     * @return mixed|null
     */
    public function getMiddleware()
    {
        if (array_key_exists($this->index, $this->middlewares)) {
            $middleware = $this->container->get($this->middlewares[$this->index]);
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
            $builder->addDefinitions($this->definition);
            try {
                $this->container = $builder->build();
            } catch (Exception $e) {
            }
        }

        return $this->container;
    }
}
