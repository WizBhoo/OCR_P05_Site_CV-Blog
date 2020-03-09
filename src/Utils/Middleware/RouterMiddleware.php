<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use MyWebsite\Utils\Router;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class MethodMiddleware.
 */
class RouterMiddleware
{
    /**
     * A Router Instance
     *
     * @var Router
     */
    protected $router;

    /**
     * RouterMiddleware constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }


    /**
     * Match route and Add params to request
     *
     * @param ServerRequestInterface $request
     * @param callable               $next
     *
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $route = $this->router->match($request);
        if (is_null($route)) {
            return $next($request);
        }
        // To add attributes to the request.
        $params = $route->getParams();
        $request = array_reduce(
            array_keys($params),
            function ($request, $key) use ($params) {
                return $request->withAttribute($key, $params[$key]);
            },
            $request
        );
        $request = $request->withAttribute(get_class($route), $route);

        return $next($request);
    }
}
