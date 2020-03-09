<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Repository\CommentRepository;
use MyWebsite\Repository\PostRepository;
use MyWebsite\Utils\RendererInterface;
use MyWebsite\Utils\Router;

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
     * AbstractController constructor.
     *
     * @param RendererInterface $renderer
     * @param Router            $router
     * @param PostRepository    $postRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(RendererInterface $renderer, Router $router, PostRepository $postRepository, CommentRepository $commentRepository)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Route callable function 404.
     *
     * @return string
     */
    public function error404(): string
    {
        return $this->renderer->renderView('site/404');
    }
}
