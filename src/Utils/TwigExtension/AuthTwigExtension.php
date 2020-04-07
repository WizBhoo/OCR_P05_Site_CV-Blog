<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\TwigExtension;

use MyWebsite\Utils\AuthInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class AuthTwigExtension.
 */
class AuthTwigExtension extends AbstractExtension
{
    /**
     * An AuthInterface Injection
     *
     * @var AuthInterface
     */
    protected $auth;

    /**
     * AuthTwigExtension constructor.
     *
     * @param AuthInterface $auth
     */
    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * AuthTwigExtension getFunction.
     *
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'current_user',
                [$this->auth, 'getUser']
            ),
        ];
    }
}
