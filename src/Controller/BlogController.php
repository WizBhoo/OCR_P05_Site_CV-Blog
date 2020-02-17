<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Utils\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class BlogController.
 */
class BlogController
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
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $path = $request->getUri()->getPath();
        $slug = $request->getAttribute('slug');
        if ($slug) {
            return $this->show($slug);
        }

        return $this->blogHome();
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
