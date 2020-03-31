<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use ArrayAccess;
use Exception;
use MyWebsite\Utils\Exception\CsrfInvalidException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TypeError;

/**
 * Class CsrfMiddleware.
 */
class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * A csrf key
     *
     * @var string
     */
    protected $formKey;

    /**
     * A csrf key in session
     *
     * @var string
     */
    protected $sessionKey;

    /**
     * A limit of csrf token in session
     *
     * @var int
     */
    protected $limit;

    /**
     * A session in ArrayAccess
     *
     * @var ArrayAccess
     */
    protected $session;

    /**
     * CsrfMiddleware constructor.
     *
     * @param ArrayAccess $session
     * @param int         $limit
     * @param string      $formKey
     * @param string      $sessionKey
     */
    public function __construct(ArrayAccess &$session, int $limit = 50, string $formKey = '_csrf', string $sessionKey = 'csrf')
    {
        $this->validSession($session);
        $this->session = &$session;
        $this->limit = $limit;
        $this->formKey = $formKey;
        $this->sessionKey = $sessionKey;
    }

    /**
     * Process CSRF check on request before sending response
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     *
     * @throws CsrfInvalidException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $params = $request->getParsedBody() ?: [];
            if (!array_key_exists($this->formKey, $params)) {
                $this->reject();
            } else {
                $csrfList = $this->session[$this->sessionKey] ?? [];
                if (!in_array($params[$this->formKey], $csrfList)) {
                    $this->reject();
                }
                $this->useToken($params[$this->formKey]);

                return $handler->handle($request);
            }
        }

        return $handler->handle($request);
    }

    /**
     * Generate a token in session
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(16));
        $csrfList = $this->session[$this->sessionKey] ?? [];
        $csrfList[] = $token;
        $this->session[$this->sessionKey] = $csrfList;
        $this->limitTokens();

        return $token;
    }

    /**
     * Getter formKey
     *
     * @return string
     */
    public function getFormKey(): string
    {
        return $this->formKey;
    }

    /**
     * Show exception if CSRF check failed
     *
     * @return void
     *
     * @throws CsrfInvalidException
     */
    protected function reject(): void
    {
        throw new CsrfInvalidException();
    }

    /**
     * Delete token used from csrf token list in session
     *
     * @param string $token
     *
     * @return void
     */
    protected function useToken(string $token): void
    {
        $tokens = array_filter(
            $this->session[$this->sessionKey],
            function ($t) use ($token) {
                return $token !== $t;
            }
        );
        $this->session[$this->sessionKey] = $tokens;
    }

    /**
     * Limit token's number in session
     *
     * @return void
     */
    protected function limitTokens(): void
    {
        $tokens = $this->session[$this->sessionKey] ?? [];
        if (count($tokens) > $this->limit) {
            array_shift($tokens);
        }
        $this->session[$this->sessionKey] = $tokens;
    }

    /**
     * Check if session is valid
     *
     * @param $session
     *
     * @return void
     */
    protected function validSession($session): void
    {
        if (!is_array($session) && !$session instanceof ArrayAccess) {
            throw new TypeError(
                'La session passée au middleware 
                CSRF n\'est pas traitable comme un tableau'
            );
        }
    }
}
