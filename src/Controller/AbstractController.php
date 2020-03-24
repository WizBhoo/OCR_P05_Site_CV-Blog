<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Repository\CommentRepository;
use MyWebsite\Repository\PostRepository;
use MyWebsite\Utils\PostUpload;
use MyWebsite\Utils\RendererInterface;
use MyWebsite\Utils\Router;
use MyWebsite\Utils\Session\FlashService;
use Swift_Mailer;

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
     * A FlashService Instance
     *
     * @var FlashService
     */
    protected $flash;

    /**
     * A PostUpload Instance
     *
     * @var PostUpload
     */
    protected $postUpload;

    /**
     * A Swift_Mailer Instance
     *
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * AbstractController constructor.
     *
     * @param RendererInterface $renderer
     * @param Router            $router
     * @param PostRepository    $postRepository
     * @param CommentRepository $commentRepository
     * @param FlashService      $flash
     * @param PostUpload        $postUpload
     * @param Swift_Mailer      $mailer
     */
    public function __construct(RendererInterface $renderer, Router $router, PostRepository $postRepository, CommentRepository $commentRepository, FlashService $flash, PostUpload $postUpload, Swift_Mailer $mailer)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->flash = $flash;
        $this->postUpload = $postUpload;
        $this->mailer = $mailer;
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
