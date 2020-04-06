<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Exception;
use MyWebsite\Entity\Post;
use MyWebsite\Utils\Session\FlashService;
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
            case '/apiadmin/comments':
                return $this->adminComments();
            case sprintf('/apiadmin/comment/%s', $id):
                if ($request->getMethod() === 'DELETE') {
                    return $this->deleteComment($request);
                }

                return $this->editComment($request);
            case '/apiadmin/users':
                return $this->adminUsers();
            case sprintf('/apiadmin/user/activate/%s', $id):
                return $this->activateUser($request);
            case sprintf('/apiadmin/user/switch/%s', $id):
                return $this->switchUserType($request);
            case sprintf('/apiadmin/user/delete/%s', $id):
                return $this->deleteUser($request);
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
            $params = $this->formParamsAuthors(['items' => $items])
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
            $this->formParamsAuthors(['items' => $items])
        );
    }

    /**
     * Route callable function adminUsers.
     *
     * @return string
     */
    public function adminUsers(): string
    {
        $users = $this->userRepository->findAllUser();

        return $this->renderer->renderView(
            'admin/adminUsers',
            compact('users')
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
                (new FlashService($this->session))
                    ->success('L\'article a bien été créé');

                return $this->router->redirect('admin.posts');
            }
            $item = $request->getParsedBody();
            $errors = $validator->getErrors();
        }

        return $this->renderer->renderView(
            'admin/createPost',
            $this->formParamsAuthors(['item' => $item, 'errors' => $errors])
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
                $this->postRepository->updatePost(
                    $slug,
                    $this->getParams($request, $item)
                );
                (new FlashService($this->session))
                    ->success('L\'article a bien été modifié');

                return $this->router->redirect('admin.posts');
            }
            $errors = $validator->getErrors();
            $params = $request->getParsedBody();
            $params['slug'] = $item->getSlug();
            $params['publishedAt'] = $item->getPublishedAt();
            $params['updatedAt'] = $item->getUpdatedAt();
            $item = $params;
        }

        if (null === $item) {
            return $this->renderer->renderView('admin/admin404');
        }

        return $this->renderer->renderView(
            'admin/editPost',
            $params = $this->formParamsAuthors(['item' => $item, 'errors' => $errors])
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
        (new FlashService($this->session))
            ->success('Le commentaire a bien été approuvé et publié');

        return $this->router->redirect('admin.comments');
    }

    /**
     * Route callable function activateUser
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function activateUser(ServerRequestInterface $request): ResponseInterface
    {
        $this->userRepository->activateUser($request->getAttribute('id'));
        (new FlashService($this->session))
            ->success('Le compte a bien été activé');

        return $this->router->redirect('admin.users');
    }

    /**
     * Route callable function switchUserType
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function switchUserType(ServerRequestInterface $request): ResponseInterface
    {
        $params['id'] = $request->getAttribute('id');
        $params['accountType'] = $this->userRepository
            ->findUserById($params['id'])
            ->getAccountType();
        if ('user' === $params['accountType']) {
            $params['accountType'] = 'admin';
        } elseif ('admin' === $params['accountType']) {
            $params['accountType'] = 'user';
        }
        $this->userRepository->switchAccountType($params);
        (new FlashService($this->session))
            ->success('Les droits de l\'utilisateur ont bien été mis à jour');

        return $this->router->redirect('admin.users');
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
        (new FlashService($this->session))
            ->success('L\'article a bien été supprimé');

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
        (new FlashService($this->session))
            ->success('Le commentaire a bien été supprimé');

        return $this->router->redirect('admin.comments');
    }

    /**
     * Route callable function deleteUser
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function deleteUser(ServerRequestInterface $request): ResponseInterface
    {
        $this->userRepository->deleteUser($request->getAttribute('id'));
        (new FlashService($this->session))
            ->success('Le compte a bien été supprimé');

        return $this->router->redirect('admin.users');
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
        $params = array_merge(
            $request->getParsedBody(),
            $request->getUploadedFiles()
        );
        $validator = (new Validator($params))
            ->required('title', 'extract', 'content')
            ->length('title', 3, 50)
            ->length('extract', 10, 255)
            ->length('content', 10)
            ->extension('image', ['jpg', 'png']);
        if (null === $request->getAttribute('slug')) {
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
        $params = array_merge(
            $request->getParsedBody(),
            $request->getUploadedFiles()
        );
        $image = $this->postUpload->upload($params['image'], $post->getImage());
        if ($image) {
            $params['image'] = $image;
        }
        $params['image'] = $post->getImage();
        $params = array_filter(
            $params,
            function ($key) {
                return in_array(
                    $key,
                    ['slug', 'title', 'user_id', 'extract', 'content', 'publishedAt', 'updatedAt', 'image']
                );
            },
            ARRAY_FILTER_USE_KEY
        );

        return $params;
    }

    /**
     * Allow to manage authors param sending to View for "select" fields
     *
     * @param array $params
     *
     * @return array
     */
    protected function formParamsAuthors(array $params): array
    {
        $params['authors'] = $this->postRepository->findListAuthors();

        return $params;
    }
}
