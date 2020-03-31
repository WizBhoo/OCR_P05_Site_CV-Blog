<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use MyWebsite\Utils\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RendererRequestMiddleware.
 */
class RendererRequestMiddleware implements MiddlewareInterface
{
    /**
     * A RendererInterface Injection
     *
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * RendererRequestMiddleware constructor.
     *
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Set up GLOBAL var "domain" and add it to renderer to be available in views
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface|void
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $domain = sprintf(
            '%s://%s%s',
            $request->getUri()->getScheme(),
            $request->getUri()->getHost(),
            $request->getUri()->getPort() ? sprintf(
                ":%s",
                $request->getUri()->getPort()
            ) : ''
        );

        $this->renderer->addGlobal('domain', $domain);

        return $handler->handle($request);
    }
}
