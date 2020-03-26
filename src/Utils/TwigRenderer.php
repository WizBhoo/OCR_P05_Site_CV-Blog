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
     * To add a path to load views.
     *
     * @param string      $namespace
     * @param string|null $path
     *
     * @return void
     */
    public function addViewPath(string $namespace, ?string $path = null): void
    {
        try {
            $this->twig->getLoader()->addPath($path, $namespace);
        } catch (LoaderError $e) {
        }
    }

    /**
     * Allows view return giving path with namespace (or not) through addPath().
     *
     * @param string $view
     * @param array  $params
     *
     * @return string
     */
    public function renderView(string $view, array $params = []): string
    {
        try {
            $view = $this->twig->render(sprintf("%s.html.twig", $view), $params);
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }

        return $view;
    }

    /**
     * Allow to add GLOBAL var to all views
     *
     * @param string $key
     * @param mixed  $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}
