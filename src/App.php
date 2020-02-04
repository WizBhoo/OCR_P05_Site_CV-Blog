<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite;

use Exception;
use GuzzleHttp\Psr7\Response;
use MyWebsite\Utils\RendererInterface;
use MyWebsite\Utils\Router;
use MyWebsite\Utils\TwigRenderer;
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
     * A TwigRenderer Interface instance.
     *
     * @var RendererInterface
     */
    protected $renderer;

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
        $this->renderer = new TwigRenderer(sprintf("%s/src/Views", dirname(__DIR__)));
        $this->renderer->addViewPath('site', sprintf("%s/Views/Site", __DIR__));
        $this->renderer->addViewPath('blog', sprintf("%s/Views/Blog", __DIR__));
        // To reference routes without using modules
        $this->router = new Router();
        $this->router->get('/', [$this, 'home'], 'site.home');
        $this->router->get('/blog', [$this, 'blogHome'], 'blog.home');
        $this->router->get('/blog/{slug:[a-z\-0-9]+}', [$this, 'show'], 'blog.show');

        $this->renderer->addGlobal('router', $this->router);

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
        return $this->renderer->renderView('site/home');
    }

    /**
     * Route callable function index.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function blogHome(ServerRequestInterface $request): string
    {
        return $this->renderer->renderView('blog/blogHome');
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
        return $this->renderer->renderView(
            'blog/show',
            ['slug' => $request->getAttribute('slug')]
        );
    }
}
