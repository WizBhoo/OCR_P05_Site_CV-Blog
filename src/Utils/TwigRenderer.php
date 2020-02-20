<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class TwigRenderer.
 */
class TwigRenderer implements RendererInterface
{
    /**
     * A Twig Environment instance.
     *
     * @var Environment
     */
    protected $twig;

    /**
     * TwigRenderer constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritDoc}
     */
    public function addViewPath(string $namespace, ?string $path = null): void
    {
        try {
            $this->twig->getLoader()->addPath($path, $namespace);
        } catch (LoaderError $e) {
        }
    }

    /**
     * {@inheritDoc}
     */
    public function renderView(string $view, array $params = []): string
    {
        try {
            return $this->twig->render(sprintf("%s.html.twig", $view), $params);
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }
}
