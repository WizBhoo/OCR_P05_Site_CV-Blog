<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Exception;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CallableFunction.
 */
class CallableFunction
{
    /**
     * A RendererInterface Instance
     *
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * CallableFunction constructor.
     *
     * @param RendererInterface $renderer
     *
     * @return void
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * CallableFunction __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $path = $request->getUri()->getPath();
        $slug = $request->getAttribute('slug');
        switch ($path) {
            case "/":
                return $this->home();
            case "/portfolio":
                return $this->portfolio();
            case sprintf("/portfolio/%s", $slug):
                return $this->project($slug);
            case "/contact":
                return $this->contact();
            case "/blog":
                return $this->blogHome();
            case sprintf("/blog/%s", $slug):
                return $this->show($slug);
            default:
                throw new Exception('Route not found');
        }
    }

    /**
     * Route callable function home.
     *
     * @return string
     */
    public function home(): string
    {
        return $this->renderer->renderView('site/home');
    }

    /**
     * Route callable function portfolio.
     *
     * @return string
     */
    public function portfolio(): string
    {
        return $this->renderer->renderView('site/portfolio');
    }

    /**
     * Route callable function for projects.
     *
     * @param string $slug
     *
     * @return string
     *
     * @throws Exception
     */
    public function project(string $slug): string
    {
        switch ($slug) {
            case "p1":
                return $this->renderer->renderView(
                    'site/works/project1',
                    ['slug' => $slug]
                );
            case "p2":
                return $this->renderer->renderView(
                    'site/works/project2',
                    ['slug' => $slug]
                );
            case "p3":
                return $this->renderer->renderView(
                    'site/works/project3',
                    ['slug' => $slug]
                );
            case "p4":
                return $this->renderer->renderView(
                    'site/works/project4',
                    ['slug' => $slug]
                );
            case "p5":
                return $this->renderer->renderView(
                    'site/works/project5',
                    ['slug' => $slug]
                );
            case "p6":
                return $this->renderer->renderView(
                    'site/works/project6',
                    ['slug' => $slug]
                );
            case "p7":
                return $this->renderer->renderView(
                    'site/works/project7',
                    ['slug' => $slug]
                );
            case "p8":
                return $this->renderer->renderView(
                    'site/works/project8',
                    ['slug' => $slug]
                );
            default:
                throw new Exception('Unexpected value');
        }
    }

    /**
     * Route callable function contact.
     *
     * @return string
     */
    public function contact(): string
    {
        return $this->renderer->renderView('site/contact');
    }

    /**
     * Route callable function blogHome.
     *
     * @return string
     */
    public function blogHome(): string
    {
        return $this->renderer->renderView('blog/blogHome');
    }

    /**
     * Route callable function show.
     *
     * @param string $slug
     *
     * @return string
     */
    public function show(string $slug): string
    {
        return $this->renderer->renderView(
            'blog/show',
            ['slug' => $slug]
        );
    }
}
