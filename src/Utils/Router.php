<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use MyWebsite\Utils\Route as Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;
use Zend\Expressive\Router\RouterInterface;

/**
 * Class Router. To register and match routes
 */
class Router
{
    /**
     * An instance of FastRouteRouter
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * Router get.
     *
     * @param string   $path
     * @param callable $callableFunction
     * @param string   $routeName
     *
     * @return void
     */
    public function get(string $path, callable $callableFunction, string $routeName): void
    {
        $this->router->addRoute(new ZendRoute($path, $callableFunction, ['GET'], $routeName));
    }

    /**
     * Router post.
     *
     * @param string   $path
     * @param callable $callableFunction
     * @param string   $routeName
     *
     * @return void
     */
    public function post(string $path, callable $callableFunction, string $routeName): void
    {
        $this->router->addRoute(new ZendRoute($path, $callableFunction, ['POST'], $routeName));
    }

    /**
     * Router delete.
     *
     * @param string   $path
     * @param callable $callableFunction
     * @param string   $routeName
     *
     * @return void
     */
    public function delete(string $path, callable $callableFunction, string $routeName): void
    {
        $this->router->addRoute(new ZendRoute($path, $callableFunction, ['DELETE'], $routeName));
    }

    /**
     * Router match.
     *
     * @param ServerRequestInterface $request
     *
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);
        if ($result->isSuccess()) {
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
        }

        return null;
    }

    /**
     * Router generateUri.
     *
     * @param string $routeName
     * @param array  $params
     *
     * @return string|null
     */
    public function generateUri(string $routeName, array $params): ?string
    {
        return $this->router->generateUri($routeName, $params);
    }
}
