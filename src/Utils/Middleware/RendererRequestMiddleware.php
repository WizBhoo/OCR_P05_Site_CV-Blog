<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use MyWebsite\Utils\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface|void
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
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

        return $delegate->process($request);
    }
}
