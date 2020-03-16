<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use MyWebsite\Utils\Route as Route;
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
     * @param string          $path
     * @param string|callable $callableFunction
     * @param string|null     $routeName
     *
     * @return void
     */
    public function get(string $path, $callableFunction, ?string $routeName = null): void
    {
        $this->router->addRoute(
            new ZendRoute(
                $path,
                $callableFunction,
                ['GET'],
                $routeName
            )
        );
    }

    /**
     * Router post.
     *
     * @param string          $path
     * @param string|callable $callableFunction
     * @param string|null     $routeName
     *
     * @return void
     */
    public function post(string $path, $callableFunction, ?string $routeName = null): void
    {
        $this->router->addRoute(
            new ZendRoute(
                $path,
                $callableFunction,
                ['POST'],
                $routeName
            )
        );
    }

    /**
     * Router delete.
     *
     * @param string          $path
     * @param string|callable $callableFunction
     * @param string|null     $routeName
     *
     * @return void
     */
    public function delete(string $path, $callableFunction, ?string $routeName = null): void
    {
        $this->router->addRoute(
            new ZendRoute(
                $path,
                $callableFunction,
                ['DELETE'],
                $routeName
            )
        );
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
     * @param string     $routeName
     * @param array|null $params
     *
     * @return string|null
     */
    public function generateUri(string $routeName, ?array $params): ?string
    {
        return $this->router->generateUri($routeName, $params);
    }

    /**
     * Return a redirection response
     *
     * @param string $path
     * @param array  $params
     *
     * @return ResponseInterface
     */
    public function redirect(string $path, array $params = []): ResponseInterface
    {

        $redirectUri = $this->generateUri($path, $params);

        return (new Response())
            ->withStatus(301)
            ->withHeader('Location', $redirectUri);
    }
}
