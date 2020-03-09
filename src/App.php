<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite;

use DI\ContainerBuilder;
use Exception;
use GuzzleHttp\Psr7\Response;
use MyWebsite\Utils\RendererInterface;
use MyWebsite\Utils\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class App.
 */
class App
{
    /**
     * A ContainerInterface instance.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * App run.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(sprintf("%s/config/config.php", dirname(__DIR__)));
        $this->container = $builder->build();

        $route = $this->container->get(Router::class)->match($request);
        if (is_null($route)) {
            return new Response(
                404,
                [],
                $this->container->get(RendererInterface::class)->renderView('site/404')
            );
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

        $callback = $route->getCallback();
        if (is_string($callback)) {
            $callback = $this->container->get($callback);
        }
        $response = call_user_func_array($callback, [$request]);
        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif (!$response instanceof ResponseInterface) {
            throw new Exception(
                'The response is not a string or an instance of ResponseInterface'
            );
        }

        return $response;
    }
}
