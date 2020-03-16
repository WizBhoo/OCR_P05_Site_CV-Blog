<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class MethodMiddleware.
 */
class MethodMiddleware
{
    /**
     * Manage method in request
     *
     * @param ServerRequestInterface $request
     * @param callable               $next
     *
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $parsedBody = $request->getParsedBody();
        if (array_key_exists('_METHOD', $parsedBody)
            && in_array($parsedBody['_METHOD'], ['DELETE', 'PUT'])
        ) {
            $request = $request->withMethod($parsedBody['_METHOD']);
        }

        return $next($request);
    }
}
