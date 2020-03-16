<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use GuzzleHttp\Psr7\Response;
use MyWebsite\Utils\RendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class MethodMiddleware.
 */
class NotFoundMiddleware
{
    /**
     * A ContainerInterface Instance
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
     * @param ServerRequestInterface $request
     *
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request): Response
    {
        return new Response(
            404,
            [],
            $this->container->get(RendererInterface::class)->renderView('site/404')
        );
    }
}
