<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Repository\PostRepository;
use MyWebsite\Utils\RendererInterface;
use MyWebsite\Utils\RouterTrait;
use Psr\Http\Message\ResponseInterface;
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
     * A PostRepository Instance
     *
     * @var PostRepository
     */
    private $postRepository;

    use RouterTrait;

    /**
     * CallableFunction constructor.
     *
     * @param RendererInterface $renderer
     * @param PostRepository    $postRepository
     *
     * @return void
     */
    public function __construct(RendererInterface $renderer, PostRepository $postRepository)
    {
        $this->renderer = $renderer;
        $this->postRepository = $postRepository;
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
        if ($request->getAttribute('slug')) {
            return $this->show($request);
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
        $posts = $this->postRepository->getAll();

        return $this->renderer->renderView('blog/blogHome', compact('posts'));
    }

    /**
     * Route callable function show.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface|string
     */
    public function show(ServerRequestInterface $request)
    {
        $post = $this->postRepository->findPost($request->getAttribute('slug'));
        if (is_null($post)) {
            return $this->renderer->renderView('site/404');
        }

        return $this->renderer->renderView(
            'blog/show',
            ['post' => $post]
        );
    }
}
