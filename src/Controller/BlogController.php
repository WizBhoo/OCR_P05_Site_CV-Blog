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
     * A Router Instance
     *
     * @var Router
     */
    protected $router;

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

    /**
     * CallableFunction constructor.
     *
     * @param RendererInterface $renderer
     * @param Router            $router
     * @param PostRepository    $postRepository
     * @param CommentRepository $commentRepository
     * @param FlashService      $flash
     */
    public function __construct(RendererInterface $renderer, Router $router, PostRepository $postRepository, CommentRepository $commentRepository, FlashService $flash)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->flash = $flash;
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
        if ($request->getMethod() === 'POST') {
            $params = $request->getParsedBody();
            $params['id'] = $post->getId();
            $this->commentRepository->insertComment($params);
            $this->flash->commentSuccess('Votre commentaire a bien été envoyé pour validation');

            return $this->router->redirect('blog.home');
        }

        return $this->renderer->renderView(
            'blog/show',
            ['post' => $post, 'comments' => $comments]
        );
    }
}
