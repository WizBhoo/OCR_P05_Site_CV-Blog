<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class RouterTwigExtension.
 */
class RouterTwigExtension extends AbstractExtension
{
    /**
     * A Router Instance.
     *
     * @var Router
     */
    protected $router;

    /**
     * RouterTwigExtension constructor.
     *
     * @param Router $router
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * RouterTwigExtension getFunction.
     *
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'pathFor']),
        ];
    }

    /**
     * RouterTwigExtension pathFor.
     *
     * @param string $path
     * @param array  $params
     *
     * @return string
     */
    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }
}
