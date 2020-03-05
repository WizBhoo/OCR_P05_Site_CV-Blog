<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Exception;
use Psr\Http\Message\ResponseInterface;
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
     * @param FlashService      $flash
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
        $id = $request->getAttribute('id');
        switch ($path) {
            case '/apiadmin/dashboard':
                return $this->dashboard();
            case '/apiadmin/posts':
                return $this->adminPosts();
            case '/apiadmin/post/new':
                return $this->createPost($request);
            case sprintf('/apiadmin/post/%s', $slug):
                if ($request->getMethod() === 'DELETE') {
                    return $this->deletePost($request);
                }

                return $this->editPost($request);
            case sprintf('/apiadmin/comment/%s', $id):
                if ($request->getMethod() === 'DELETE') {
                    return $this->deleteComment($request);
                }

                return $this->editComment($request);
            case '/apiadmin/comments':
                return $this->adminComments();
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
     * Route callable function adminComments.
     *
     * @return string
     */
    public function adminComments(): string
    {
        $items = $this->commentRepository->findUnapproved();

        return $this->renderer->renderView('admin/adminComments', compact('items'));
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
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postRepository->insertPost($params);
                $this->flash->success('L\'article a bien été créé');

                return $this->router->redirect('admin.posts');
            }
            $item = $params;
            $errors = $validator->getErrors();
        }

        return $this->renderer->renderView(
            'admin/createPost',
            ['item' => $item, 'errors' => $errors]
        );
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
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postRepository->updatePost($slug, $params);
                $this->flash->success('L\'article a bien été modifié');

                return $this->router->redirect('admin.posts');
            }
            $errors = $validator->getErrors();
            $params['slug'] = $item->getSlug();
            $params['nameAuthor'] = $item->nameAuthor;
            $params['publiDate'] = $item->getPubliDate();
            $params['modifDate'] = $item->getModifDate();
            $item = $params;
        }

        if (is_null($item)) {
            return $this->renderer->renderView('admin/admin404');
        }

        return $this->renderer->renderView(
            'admin/editPost',
            ['item' => $item, 'errors' => $errors]
        );
    }

    /**
     * Route callable function editComment
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function editComment(ServerRequestInterface $request)
    {
        $this->commentRepository->updateComment($request->getAttribute('id'));
        $this->flash->success('Le commentaire a bien été approuvé et publié');

        return $this->router->redirect('admin.comments');
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

    /**
     * Route callable function deleteComment
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function deleteComment(ServerRequestInterface $request)
    {
        $this->commentRepository->deleteComment($request->getAttribute('id'));
        $this->flash->success('Le commentaire a bien été supprimé');

        return $this->router->redirect('admin.comments');
    }

    /**
     * Validator instance with defined rules
     *
     * @param ServerRequestInterface $request
     *
     * @return Validator
     */
    public function getValidator(ServerRequestInterface $request): Validator
    {
        return (new Validator($request->getParsedBody()))
            ->required('title', 'extract', 'content')
            ->length('title', 2, 50)
            ->length('extract', 10, 255)
            ->length('content', 10)
            ->slug('slug');
    }
}
