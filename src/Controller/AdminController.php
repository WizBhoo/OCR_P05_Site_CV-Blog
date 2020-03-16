<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Exception;
use MyWebsite\Entity\Post;
use MyWebsite\Utils\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AdminController.
 */
class AdminController extends AbstractController
{
    /**
     * AdminController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface|string
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

        return $this->renderer->renderView(
            'admin/adminPosts',
            $params = $this->formParams(['items' => $items])
        );
    }

    /**
     * Route callable function adminComments.
     *
     * @return string
     */
    public function adminComments(): string
    {
        $items = $this->commentRepository->findAllComment();

        return $this->renderer->renderView(
            'admin/adminComments',
            $params = $this->formParams(['items' => $items])
        );
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
        $item = new Post();
        if ($request->getMethod() === 'POST') {
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postRepository->insertPost($this->getParams($request, $item));
                $this->flash->success('L\'article a bien été créé');

                return $this->router->redirect('admin.posts');
            }
            $item = $request->getParsedBody();
            $errors = $validator->getErrors();
        }

        return $this->renderer->renderView(
            'admin/createPost',
            $params = $this->formParams(['item' => $item, 'errors' => $errors])
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
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postRepository->updatePost($slug, $this->getParams($request, $item));
                $this->flash->success('L\'article a bien été modifié');

                return $this->router->redirect('admin.posts');
            }
            $errors = $validator->getErrors();
            $params = $request->getParsedBody();
            $params['publishedAt'] = $item->getPublishedAt();
            $params['updatedAt'] = $item->getUpdatedAt();
            $item = $params;
        }

        if (is_null($item)) {
            return $this->renderer->renderView('admin/admin404');
        }

        return $this->renderer->renderView(
            'admin/editPost',
            $params = $this->formParams(['item' => $item, 'errors' => $errors])
        );
    }

    /**
     * Route callable function editComment
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function editComment(ServerRequestInterface $request): ResponseInterface
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
    public function deletePost(ServerRequestInterface $request): ResponseInterface
    {
        $post = $this->postRepository->findPost($request->getAttribute('slug'));
        $this->postUpload->delete($post->getImage());
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
    public function deleteComment(ServerRequestInterface $request): ResponseInterface
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
        $params = array_merge($request->getParsedBody(), $request->getUploadedFiles());
        $validator = (new Validator($params))
            ->required('title', 'extract', 'content')
            ->length('title', 2, 50)
            ->length('extract', 10, 255)
            ->length('content', 10)
            ->extension('image', ['jpg', 'png'])
        ;

        if (is_null($request->getAttribute('slug'))) {
            $validator->uploaded('image');
        }

        return $validator;
    }

    /**
     * Retrieve params from request formatted in array
     *
     * @param ServerRequestInterface $request
     * @param Post                   $post
     *
     * @return array
     */
    protected function getParams(ServerRequestInterface $request, Post $post): array
    {
        $params = array_merge($request->getParsedBody(), $request->getUploadedFiles());
        $image = $this->postUpload->upload($params['image'], $post->getImage());
        if ($image) {
            $params['image'] = $image;
        } else {
            $params['image'] = $post->getImage();
        }
        $params = array_filter($params, function ($key) {
            return in_array($key, ['slug', 'title', 'user_id', 'extract', 'content', 'publishedAt', 'updatedAt', 'image']);
        }, ARRAY_FILTER_USE_KEY);

        return $params;
    }

    /**
     * Allow to manage params sending to View
     *
     * @param array $params
     *
     * @return array
     */
    protected function formParams(array $params): array
    {
        $params['authors'] = $this->postRepository->findListAuthors();

        return $params;
    }

    /**
     * Allow to manage params sending to View
     *
     * @param array $params
     *
     * @return array
     */
    protected function formParams(array $params): array
    {
        $params['authors'] = $this->postRepository->findListAuthors();

        return $params;
    }
}
