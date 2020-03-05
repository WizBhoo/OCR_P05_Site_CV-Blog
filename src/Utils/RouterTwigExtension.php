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
            new TwigFunction('is_subpath', [$this, 'isSubPath']),
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

    /**
     * RouterTwigExtension isSubPath.
     *
     * @param string     $path
     * @param array|null $params
     *
     * @return bool
     */
    public function isSubPath(string $path, ?array $params = []): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $expectedUri = $this->router->generateUri($path, $params);

        return strpos($uri, $expectedUri) !== false;
    }
}
