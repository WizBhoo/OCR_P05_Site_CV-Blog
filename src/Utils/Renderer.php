<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

/**
 * Class Renderer.
 */
class Renderer
{
    const DEFAULT_NAMESPACE = '__MAIN';

    /**
     * Contain paths used by renderer
     *
     * @var array
     */
    protected $paths = [];

    /**
     * Accessible global vars for all views.
     *
     * @var array
     */
    protected $globals = [];

    /**
     * Renderer addViewPath.
     * To add a path to load views.
     *
     * @param string      $namespace
     * @param string|null $path
     *
     * @return void
     */
    public function addViewPath(string $namespace, ?string $path = null): void
    {
        if (is_null($path)) {
            $this->paths[self::DEFAULT_NAMESPACE] = $namespace;
        } else {
            $this->paths[$namespace] = $path;
        }
    }

    /**
     * Renderer render.
     * Allows view return giving path with namespace (or not) through addPath().
     *
     * @param string $view
     * @param array  $params
     *
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        $path = $this->__rendererPath($view, $params);
        ob_start();
        extract($this->globals);
        extract($params);
        include $path;

        return ob_get_clean();
    }

    /**
     * Renderer addGlobal.
     * Allows to add global var to all views.
     *
     * @param string $key
     * @param $value
     *
     * @return void
     */
    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    /**
     * Renderer __rendererPath.
     *
     * @param string $view
     * @param array  $params
     *
     * @return string
     */
    private function __rendererPath(string $view, array $params = []): string
    {
        if ($this->__hasNamespace($view)) {
            $path = sprintf("%s.php", $this->__replaceNamespace($view));
        } else {
            $path = sprintf(
                "%s%s%s.php",
                $this->paths[self::DEFAULT_NAMESPACE],
                DIRECTORY_SEPARATOR,
                $view
            );
        }

        return $path;
    }

    /**
     * Renderer __hasNamespace.
     *
     * @param string $view
     *
     * @return bool
     */
    private function __hasNamespace(string $view): bool
    {
        return $view[0] === '@';
    }

    /**
     * Renderer __getNamespace.
     *
     * @param string $view
     *
     * @return string
     */
    private function __getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') - 1);
    }

    /**
     * Renderer __replaceNamespace.
     *
     * @param string $view
     *
     * @return string
     */
    private function __replaceNamespace(string $view): string
    {
        $namespace = $this->__getNamespace($view);

        return str_replace(
            sprintf("@%s", $namespace),
            $this->paths[$namespace],
            $view
        );
    }
}
