<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

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
            CallableFunction::class,
            'site.home'
        );
        $router->get(
            $container->get('portfolio.prefix'),
            CallableFunction::class,
            'site.portfolio'
        );
        $router->get(
            sprintf("%s/{slug:[a-z\-0-9]+}", $container->get('portfolio.prefix')),
            CallableFunction::class,
            'site.project'
        );
        $router->get(
            $container->get('contact.prefix'),
            CallableFunction::class,
            'site.contact'
        );
        $router->get(
            $container->get('blog.prefix'),
            CallableFunction::class,
            'blog.home'
        );
        $router->get(
            sprintf("%s/{slug:[a-z\-0-9]+}", $container->get('blog.prefix')),
            CallableFunction::class,
            'blog.show'
        );

        return $router;
    }
}
