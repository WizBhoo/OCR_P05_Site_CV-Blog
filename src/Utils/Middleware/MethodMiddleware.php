<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MethodMiddleware.
 */
class MethodMiddleware implements MiddlewareInterface
{
    /**
     * Manage method in request
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        if (array_key_exists('_METHOD', $parsedBody)
            && in_array($parsedBody['_METHOD'], ['DELETE', 'PUT'])
        ) {
            $request = $request->withMethod($parsedBody['_METHOD']);
        }

        return $handler->handle($request);
    }
}
