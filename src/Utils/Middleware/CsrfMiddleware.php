<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Middleware;

use ArrayAccess;
use Exception;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TypeError;

/**
 * Class CsrfMiddleware.
 */
class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $formKey;

    /**
     * @var string
     */
    protected $sessionKey;

    /**
     * @var int
     */
    protected $limit;

    /**
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
    public function __construct(&$session, int $limit = 50, string $formKey = '_csrf', string $sessionKey = 'csrf')
    {
        $this->validSession($session);
        $this->session = &$session;
        $this->limit = $limit;
        $this->formKey = $formKey;
        $this->sessionKey = $sessionKey;
    }

    /**
     * @inheritDoc
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $params = $request->getParsedBody() ?: [];
            if (!array_key_exists($this->formKey, $params)) {
                $this->reject();
            } else {
                $csrfList = $this->session[$this->sessionKey] ?? [];
                if (in_array($params[$this->formKey], $csrfList)) {
                    $this->useToken($params[$this->formKey]);
                    $delegate->process($request);
                } else {
                    $this->reject();
                }
            }
        }

        return $delegate->process($request);
    }

    /**
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
     * @return void
     *
     * @throws Exception
     */
    private function reject(): void
    {
        throw new Exception();
    }

    /**
     * @param string $token
     *
     * @return void
     */
    private function useToken(string $token): void
    {
        $tokens = array_filter($this->session[$this->sessionKey], function ($t) use ($token) {
            return $token !== $t;
        });
        $this->session[$this->sessionKey] = $tokens;
    }

    /**
     * @return void
     */
    private function limitTokens(): void
    {
        $tokens = $this->session[$this->sessionKey] ?? [];
        if (count($tokens) > $this->limit) {
            array_shift($tokens);
        }
        $this->session[$this->sessionKey] = $tokens;
    }

    /**
     * @param $session
     */
    private function validSession($session)
    {
        if (!is_array($session) && !$session instanceof ArrayAccess) {
            throw new TypeError(
                'La session passée au middleware CSRF n\'est pas traitable comme un tableau'
            );
        }
    }
}
