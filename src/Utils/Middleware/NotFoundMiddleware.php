<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use GuzzleHttp\Psr7\Response;
use MyWebsite\Utils\RendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MethodMiddleware.
 */
class NotFoundMiddleware implements MiddlewareInterface
{
    /**
     * A ContainerInterface Injection
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * NotFoundMiddleware constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Return a 404 Response in case of not route matched
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response(
            404,
            [],
            $this->container->get(RendererInterface::class)->renderView('site/404')
        );
    }
}
