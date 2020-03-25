<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use MyWebsite\Controller\AccountController;
use MyWebsite\Controller\AccountEditController;
use MyWebsite\Controller\AdminController;
use MyWebsite\Controller\BlogController;
use MyWebsite\Controller\LoginAttemptController;
use MyWebsite\Controller\LoginController;
use MyWebsite\Controller\LogoutController;
use MyWebsite\Controller\SignUpController;
use MyWebsite\Controller\SiteController;
use MyWebsite\Utils\Middleware\LoggedInMiddleware;
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
        // To reference GET routes
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
        $router->get(
            $container->get('auth.login'),
            LoginController::class,
            'auth.login'
        );
        $router->get(
            $container->get('account.signup'),
            SignUpController::class,
            'account.signup'
        );
        $router->get(
            $container->get('account.profile'),
            [LoggedInMiddleware::class, AccountController::class],
            'account.profile'
        );
        $router->get(
            sprintf("%s/dashboard", $container->get('admin.prefix')),
            AdminController::class,
            'admin.dashboard'
        );
        $router->get(
            sprintf("%s/posts", $container->get('admin.prefix')),
            AdminController::class,
            'admin.posts'
        );
        $router->get(
            sprintf("%s/post/new", $container->get('admin.prefix')),
            AdminController::class,
            'admin.post.create'
        );
        $router->get(
            sprintf("%s/post/{slug:[a-z\-0-9]+}", $container->get('admin.prefix')),
            AdminController::class,
            'admin.post.edit'
        );
        $router->get(
            sprintf("%s/comments", $container->get('admin.prefix')),
            AdminController::class,
            'admin.comments'
        );
        $router->get(
            sprintf("%s/users", $container->get('admin.prefix')),
            AdminController::class,
            'admin.users'
        );
        $router->get(
            sprintf("%s/404", $container->get('admin.prefix')),
            AdminController::class,
            'admin.404'
        );
        // To reference POST routes
        $router->post(
            $container->get('auth.login'),
            LoginAttemptController::class
        );
        $router->post(
            $container->get('auth.logout'),
            LogoutController::class,
            'auth.logout'
        );
        $router->post(
            $container->get('account.signup'),
            SignUpController::class
        );
        $router->post(
            $container->get('account.profile'),
            [LoggedInMiddleware::class, AccountEditController::class]
        );
        $router->post(
            sprintf("%s/post/new", $container->get('admin.prefix')),
            AdminController::class
        );
        $router->post(
            sprintf("%s/post/{slug:[a-z\-0-9]+}", $container->get('admin.prefix')),
            AdminController::class
        );
        $router->post(
            sprintf("%s/{slug:[a-z\-0-9]+}", $container->get('blog.prefix')),
            BlogController::class
        );
        $router->post(
            sprintf("%s/comment/{id:[0-9]+}", $container->get('admin.prefix')),
            AdminController::class,
            'admin.comment.edit'
        );
        $router->post(
            sprintf("%s/user/activate/{id:[0-9]+}", $container->get('admin.prefix')),
            AdminController::class,
            'admin.user.activate'
        );
        $router->post(
            sprintf("%s/user/switch/{id:[0-9]+}", $container->get('admin.prefix')),
            AdminController::class,
            'admin.user.switch'
        );
        // To reference DELETE routes
        $router->delete(
            sprintf("%s/post/{slug:[a-z\-0-9]+}", $container->get('admin.prefix')),
            AdminController::class,
            'admin.post.delete'
        );
        $router->delete(
            sprintf("%s/comment/{id:[0-9]+}", $container->get('admin.prefix')),
            AdminController::class,
            'admin.comment.delete'
        );
        $router->delete(
            sprintf("%s/user/delete/{id:[0-9]+}", $container->get('admin.prefix')),
            AdminController::class,
            'admin.user.delete'
        );

        return $router;
    }
}
