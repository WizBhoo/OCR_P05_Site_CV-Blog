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
