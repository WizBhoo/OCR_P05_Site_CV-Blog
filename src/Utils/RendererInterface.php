<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

/**
 * Interface RendererInterface.
 */
interface RendererInterface
{
    /**
     * RendererInterface addViewPath.
     * To add a path to load views.
     *
     * @param string      $namespace
     * @param string|null $path
     *
     * @return void
     */
    public function addViewPath(string $namespace, ?string $path = null): void;

    /**
     * RendererInterface renderView.
     * Allows view return giving path with namespace (or not) through addPath().
     *
     * @param string $view
     * @param array  $params
     *
     * @return string
     */
    public function renderView(string $view, array $params = []): string;

    /**
     * RendererInterface addGlobal.
     * Allows to add global var to all views.
     *
     * @param string $key
     * @param $value
     *
     * @return void
     */
    public function addGlobal(string $key, $value): void;
}
