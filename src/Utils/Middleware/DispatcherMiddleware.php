<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use Exception;
use GuzzleHttp\Psr7\Response;
use MyWebsite\Utils\Route;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class DispatcherMiddleware.
 */
class DispatcherMiddleware
{
    /**
     * A ContainerInterface Instance
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * RouterMiddleware constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * Retrieve Callback and return appropriate Response
     *
     * @param ServerRequestInterface $request
     * @param callable               $next
     *
     * @return Response|mixed
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $route = $request->getAttribute(Route::class);
        if (is_null($route)) {
            return $next($request);
        }
        $callback = $route->getCallback();
        if (is_string($callback)) {
            $callback = $this->container->get($callback);
        }
        $response = call_user_func_array($callback, [$request]);
        if (is_string($response)) {
            $response = new Response(
                200,
                [],
                $response
            );
        } elseif (!$response instanceof ResponseInterface) {
            throw new Exception(
                'The response is not a string or an instance of ResponseInterface'
            );
        }

        return $response;
    }
}
