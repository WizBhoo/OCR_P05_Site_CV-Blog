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
        if ($slug) {
            return $this->show($slug);
        }
        switch ($path) {
            case "/":
                return $this->home();
            case "/portfolio":
                return $this->portfolio();
            case "/contact":
                return $this->contact();
            case "/blog":
                return $this->blogHome();
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
