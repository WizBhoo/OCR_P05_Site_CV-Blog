<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class BlogController.
 */
class BlogController extends AbstractController
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
    protected $postRepository;

    /**
     * A CommentRepository Instance
     *
     * @var CommentRepository
     */
    protected $commentRepository;

    use RouterTrait;

    /**
     * CallableFunction constructor.
     *
     * @param RendererInterface $renderer
     * @param PostRepository    $postRepository
     * @param CommentRepository $commentRepository
     *
     * @return void
     */
    public function __construct(RendererInterface $renderer, PostRepository $postRepository, CommentRepository $commentRepository)
    {
        $this->renderer = $renderer;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
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
        $slug = $request->getAttribute('slug');
        $post = $this->postRepository->findPost($slug);
        $comments = $this->commentRepository->findComments($slug);
        if (is_null($post)) {
            return $this->renderer->renderView('site/404');
        }

        return $this->renderer->renderView(
            'blog/show',
            ['post' => $post, 'comments' => $comments]
        );
    }
}
