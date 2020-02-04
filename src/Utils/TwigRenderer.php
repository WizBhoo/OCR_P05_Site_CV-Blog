<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

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
     * A Twig Loader instance.
     *
     * @var FilesystemLoader
     */
    protected $loader;

    /**
     * TwigRenderer constructor.
     *
     * @param string $path
     *
     * @return void
     */
    public function __construct(string $path)
    {
        $this->loader = new FilesystemLoader($path);
        $this->twig = new Environment($this->loader, []);
    }

    /**
     * {@inheritDoc}
     */
    public function addViewPath(string $namespace, ?string $path = null): void
    {
        try {
            $this->loader->addPath($path, $namespace);
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

    /**
     * {@inheritDoc}
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}
