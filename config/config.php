<?php

use MyWebsite\Utils\Auth\DatabaseAuth;
use MyWebsite\Utils\AuthInterface;
use MyWebsite\Utils\ConnectDb;
use MyWebsite\Utils\SwiftMailerFactory;
use MyWebsite\Utils\TwigExtension\AuthTwigExtension;
use MyWebsite\Utils\TwigExtension\CsrfTwigExtension;
use MyWebsite\Utils\TwigExtension\FlashTwigExtension;
use MyWebsite\Utils\TwigExtension\FormTwigExtension;
use MyWebsite\Utils\Middleware\CsrfMiddleware;
use MyWebsite\Utils\Middleware\ForbiddenMiddleware;
use MyWebsite\Utils\Router;
use MyWebsite\Utils\RouterFactory;
use MyWebsite\Utils\RendererInterface;
use MyWebsite\Utils\TwigExtension\RouterTwigExtension;
use MyWebsite\Utils\Session\PHPSession;
use MyWebsite\Utils\Session\SessionInterface;
use MyWebsite\Utils\TwigRendererFactory;
use Symfony\Component\Dotenv\Dotenv;

use function DI\create;
use function DI\factory;
use function DI\get;

//Dot Env Loader
$dotenv = new Dotenv();
$dotenv->loadEnv('../.env');

return [
    //Router config keys
    'site.prefix' => '/',
    '404.prefix' => '/404',
    'portfolio.prefix' => '/portfolio',
    'contact.prefix' => '/contact',
    'blog.prefix' => '/blog',
    'auth.login' => '/login',
    'auth.logout' => '/logout',
    'auth.password' => '/forgotten-password',
    'auth.reset' => '/reset-password',
    'account.signup' => '/signup',
    'account.profile' => '/user-profile',
    'admin.prefix' => '/apiadmin',

    //Twig config keys
    'default_views.path' => "../src/Views",
    'site_views.path' => "../Views/site",
    'blog_views.path' => "../Views/blog",
    'auth_views.path' => "../Views/auth",
    'account_views.path' => "../Views/account",
    'admin_views.path' => "../Views/admin",
    'email_views.path' => "../Views/email",

    //Twig extensions
    'twig.extensions' => [
        get(RouterTwigExtension::class),
        get(FlashTwigExtension::class),
        get(FormTwigExtension::class),
        get(CsrfTwigExtension::class),
        get(AuthTwigExtension::class),
    ],

    AuthInterface::class => get(DatabaseAuth::class),
    ForbiddenMiddleware::class => create()->constructor(
        get('auth.login'),
        get(SessionInterface::class)
    ),
    SessionInterface::class => get(PHPSession::class),
    CsrfMiddleware::class => create()->constructor(get(SessionInterface::class)),
    Router::class => factory(RouterFactory::class),
    RendererInterface::class => factory(TwigRendererFactory::class),
    PDO::class => ConnectDb::getInstance()->getConnection(),

    //Mailer config
    Swift_Mailer::class => factory(SwiftMailerFactory::class),
];
