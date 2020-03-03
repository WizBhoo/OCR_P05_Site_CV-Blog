<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AdminController.
 */
class AdminController extends AbstractController
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
     * CallableFunction constructor.
     *
     * @param RendererInterface $renderer
     * @param Router            $router
     * @param PostRepository    $postRepository
     *
     * @return void
     */
    public function __construct(RendererInterface $renderer, Router $router, PostRepository $postRepository)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postRepository = $postRepository;
    }

    /**
     * AdminController __invoke.
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
            case '/apiadmin/dashboard':
                return $this->dashboard();
            case '/apiadmin/posts':
                return $this->adminPosts();
            case sprintf('/apiadmin/post/%s', $slug):
                if ($request->getMethod() === 'DELETE') {
                    return $this->deletePost($request);
                }

                return $this->editPost($request);
            case sprintf('/apiadmin/post/new'):
                return $this->createPost($request);
            default:
                throw new Exception('Route not found');
        }
    }

    /**
     * Route callable function dashboard.
     *
     * @return string
     */
    public function dashboard(): string
    {
        return $this->renderer->renderView('admin/dashboard');
    }

    /**
     * Route callable function adminPosts.
     *
     * @return string
     */
    public function adminPosts(): string
    {
        $items = $this->postRepository->getAll();

        return $this->renderer->renderView('admin/adminPosts', compact('items'));
    }

    /**
     * Route callable function createPost
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface|string
     */
    public function createPost(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'POST') {
            $params = $request->getParsedBody();
            $this->postRepository->insertPost($params);
            $this->flash->success('L\'article a bien été créé');

            return $this->router->redirect('admin.posts');
        }

        return $this->renderer->renderView('admin/createPost');
    }

    /**
     * Route callable function editPost
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface|string
     */
    public function editPost(ServerRequestInterface $request)
    {
        $slug = $request->getAttribute('slug');
        $item = $this->postRepository->findPost($slug);

        if ($request->getMethod() === 'POST') {
            $params = $request->getParsedBody();
            $this->postRepository->updatePost($slug, $params);
            $this->flash->success('L\'article a bien été modifié');

            return $this->router->redirect('admin.posts');
        }

        if (is_null($item)) {
            return $this->renderer->renderView('admin/admin404');
        }

        return $this->renderer->renderView(
            'admin/editPost',
            ['item' => $item]
        );
    }

    /**
     * Route callable function deletePost
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function deletePost(ServerRequestInterface $request)
    {
        $this->postRepository->deletePost($request->getAttribute('slug'));
        $this->flash->success('L\'article a bien été supprimé');

        return $this->router->redirect('admin.posts');
    }
}
