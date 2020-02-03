<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite;

use Exception;
use GuzzleHttp\Psr7\Response;
use MyWebsite\Utils\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class App. To load modules
 */
class App
{
    /**
     * A Router instance.
     *
     * @var Router
     */
    protected $router;

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
        // To reference routes without using modules
        $this->router = new Router();
        $this->router->get('/', [$this, 'home'], 'site.home');
        $this->router->get('/blog', [$this, 'index'], 'blog.index');
        $this->router->get('/blog/{slug:[a-z\-]+}', [$this, 'show'], 'blog.show');

        $route = $this->router->match($request);
        if (is_null($route)) {
            return new Response(404, [], '<h1>Erreur 404</h1>');
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

        $response = call_user_func_array($route->getCallback(), [$request]);
        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif (!$response instanceof ResponseInterface) {
            throw new Exception(
                'The response is not a string or an instance of ResponseInterface'
            );
        }

        return $response;
    }

    /**
     * Route callable function home.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function home(ServerRequestInterface $request): string
    {
        return '<h1>Bienvenue sur le site</h1>';
    }

    /**
     * Route callable function index.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function index(ServerRequestInterface $request): string
    {
        return '<h1>Bienvenue sur le blog</h1>';
    }

    /**
     * Route callable function show.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function show(ServerRequestInterface $request): string
    {
        return sprintf(
            "<h1>Bienvenue sur l'Article %s</h1>",
            $request->getAttribute('slug')
        );
    }
}
