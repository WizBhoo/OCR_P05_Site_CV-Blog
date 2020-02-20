<?php

use MyWebsite\Utils\ConnectDb;
use MyWebsite\Utils\Router;
use MyWebsite\Utils\RouterFactory;
use MyWebsite\Utils\RendererInterface;
use MyWebsite\Utils\RouterTwigExtension;
use MyWebsite\Utils\TwigRendererFactory;

use function DI\factory;
use function DI\get;

return [
    //Router config keys
    'site.prefix' => '/',
    '404.prefix' => '/404',
    'portfolio.prefix' => '/portfolio',
    'contact.prefix' => '/contact',
    'blog.prefix' => '/blog',
    'admin.prefix' => '/apiadmin',

    //Twig config keys
    'default_views.path' => sprintf("%s/src/Views", dirname(__DIR__)),
    'site_views.path' => sprintf("%s/Views/site", __DIR__),
    'blog_views.path' => sprintf("%s/Views/blog", __DIR__),
    'admin_views.path' => sprintf("%s/Views/admin", __DIR__),

    //Twig extensions
    'twig.extensions' => [
        get(RouterTwigExtension::class),
    ],

    Router::class => factory(RouterFactory::class),
    RendererInterface::class => factory(TwigRendererFactory::class),
    PDO::class => ConnectDb::getInstance()->getConnection(),
];
