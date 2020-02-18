<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use MyWebsite\Controller\BlogController;
use MyWebsite\Controller\SiteController;
use Psr\Container\ContainerInterface;

/**
 * Class RouterFactory.
 */
class RouterFactory
{
    /**
     * RouterFactory __invoke.
     *
     * @param ContainerInterface $container
     *
     * @return Router
     */
    public function __invoke(ContainerInterface $container): Router
    {
        $router = new Router();
        // To reference routes
        $router->get(
            $container->get('site.prefix'),
            SiteController::class,
            'site.home'
        );
        $router->get(
            $container->get('portfolio.prefix'),
            SiteController::class,
            'site.portfolio'
        );
        $router->get(
            sprintf("%s/{slug:[a-z\-0-9]+}", $container->get('portfolio.prefix')),
            SiteController::class,
            'site.project'
        );
        $router->get(
            $container->get('contact.prefix'),
            SiteController::class,
            'site.contact'
        );
        $router->get(
            $container->get('404.prefix'),
            SiteController::class,
            'site.404'
        );
        $router->get(
            $container->get('blog.prefix'),
            BlogController::class,
            'blog.home'
        );
        $router->get(
            sprintf("%s/{slug:[a-z\-0-9]+}", $container->get('blog.prefix')),
            BlogController::class,
            'blog.show'
        );

        return $router;
    }
}
