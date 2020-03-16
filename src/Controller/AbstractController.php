<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Repository\CommentRepository;
use MyWebsite\Repository\PostRepository;
use MyWebsite\Repository\UserRepository;
use MyWebsite\Utils\Auth\DatabaseAuth;
use MyWebsite\Utils\PostUpload;
use MyWebsite\Utils\RendererInterface;
use MyWebsite\Utils\Router;
use MyWebsite\Utils\Session\SessionInterface;

/**
 * Class AbstractController.
 */
abstract class AbstractController
{
    /**
     * A RendererInterface Instance
     *
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * A Router Instance
     *
     * @var Router
     */
    protected $router;

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
     * A PostUpload Instance
     *
     * @var PostUpload
     */
    protected $postUpload;

    /**
     * A UserRepository Instance
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * A DatabaseAuth Instance
     *
     * @var DatabaseAuth
     */
    protected $auth;

    /**
     * A SessionInterface Injection
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * AbstractController constructor.
     *
     * @param RendererInterface $renderer
     * @param Router            $router
     * @param PostRepository    $postRepository
     * @param CommentRepository $commentRepository
     * @param PostUpload        $postUpload
     * @param UserRepository    $userRepository
     * @param DatabaseAuth      $auth
     * @param SessionInterface  $session
     */
    public function __construct(RendererInterface $renderer, Router $router, PostRepository $postRepository, CommentRepository $commentRepository, PostUpload $postUpload, UserRepository $userRepository, DatabaseAuth $auth, SessionInterface $session)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->postUpload = $postUpload;
        $this->userRepository = $userRepository;
        $this->auth = $auth;
        $this->session = $session;
    }

    /**
     * Route callable function 404.
     *
     * @return string
     */
    protected function error404(): string
    {
        return $this->renderer->renderView('site/404');
    }
}
