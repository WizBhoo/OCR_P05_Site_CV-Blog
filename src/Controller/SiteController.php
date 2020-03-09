<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Exception;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class SiteController.
 */
class SiteController extends AbstractController
{
    /**
     * SiteController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request): string
    {
        $path = $request->getUri()->getPath();
        $slug = $request->getAttribute('slug');
        switch ($path) {
            case '/':
                return $this->home();
            case '/portfolio':
                return $this->portfolio();
            case sprintf('/portfolio/%s', $slug):
                return $this->project($slug);
            case '/contact':
                return $this->contact();
            default:
                throw new Exception('Route not found');
        }
    }

    /**
     * Route callable function home.
     *
     * @return string
     */
    public function home(): string
    {
        return $this->renderer->renderView('site/home');
    }

    /**
     * Route callable function portfolio.
     *
     * @return string
     */
    public function portfolio(): string
    {
        return $this->renderer->renderView('site/portfolio');
    }

    /**
     * Route callable function for projects.
     *
     * @param string $slug
     *
     * @return string
     */
    public function project(string $slug): string
    {
        switch ($slug) {
            case 'p1':
                return $this->renderer->renderView(
                    'site/works/project1',
                    ['slug' => $slug]
                );
            case 'p2':
                return $this->renderer->renderView(
                    'site/works/project2',
                    ['slug' => $slug]
                );
            case 'p3':
                return $this->renderer->renderView(
                    'site/works/project3',
                    ['slug' => $slug]
                );
            case 'p4':
                return $this->renderer->renderView(
                    'site/works/project4',
                    ['slug' => $slug]
                );
            case 'p5':
                return $this->renderer->renderView(
                    'site/works/project5',
                    ['slug' => $slug]
                );
            case 'p6':
                return $this->renderer->renderView(
                    'site/works/project6',
                    ['slug' => $slug]
                );
            case 'p7':
                return $this->renderer->renderView(
                    'site/works/project7',
                    ['slug' => $slug]
                );
            case 'p8':
                return $this->renderer->renderView(
                    'site/works/project8',
                    ['slug' => $slug]
                );
            default:
                return $this->error404();
        }
    }

    /**
     * Route callable function contact.
     *
     * @return string
     */
    public function contact(): string
    {
        return $this->renderer->renderView('site/contact');
    }
}
