<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Utils\Session\FlashService;
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
     * @return ResponseInterface|string
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

        return $this->renderer->renderView(
            'blog/blogHome',
            compact('posts')
        );
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
        $params = $this->getParams($request);
        if (null === $params['post']) {
            return $this->renderer->renderView('site/404');
        }
        if ($request->getMethod() === 'POST') {
            $params = $request->getParsedBody();
            $slug = $request->getAttribute('slug');
            $user = $this->auth->getUser();
            $params['id'] = $this->postRepository->findPost($slug)->getId();
            $params['user_id'] = $user->getId();
            unset($params['_csrf']);
            $this->commentRepository->insertComment($params);
            (new FlashService($this->session))
                ->commentSuccess(
                    'Votre commentaire a bien été envoyé pour validation'
                );

            return $this->router->redirect('blog.home');
        }

        return $this->renderer->renderView('blog/show', $params);
    }

    /**
     * Allow to manage params sending to View
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    protected function getParams(ServerRequestInterface $request): array
    {
        $slug = $request->getAttribute('slug');
        $post = $this->postRepository->findPost($slug);
        $comments = $this->commentRepository->findComments($slug);

        return [
            'slug' => $slug,
            'post' => $post,
            'comments' => $comments,
            'authors' => $this->postRepository->findListAuthors(),
        ];
    }
}
