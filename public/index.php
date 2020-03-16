<?php

require '../vendor/autoload.php';

use GuzzleHttp\Psr7\ServerRequest;
use Middlewares\Whoops;
use MyWebsite\App;
use MyWebsite\Utils\Middleware\CsrfMiddleware;
use MyWebsite\Utils\Middleware\DispatcherMiddleware;
use MyWebsite\Utils\Middleware\ForbiddenMiddleware;
use MyWebsite\Utils\Middleware\LoggedInMiddleware;
use MyWebsite\Utils\Middleware\MethodMiddleware;
use MyWebsite\Utils\Middleware\NotFoundMiddleware;
use MyWebsite\Utils\Middleware\RouterMiddleware;

use function Http\Response\send;

$app = new App(sprintf("%s/config/config.php", dirname(__DIR__)));
$container = $app->getContainer();
$app->pipe(Whoops::class)
    ->pipe(ForbiddenMiddleware::class)
    ->pipe($container->get('admin.prefix'), LoggedInMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class)
;

try {
    $response = $app->run(ServerRequest::fromGlobals());
    send($response);
} catch (Exception $e) {
}
