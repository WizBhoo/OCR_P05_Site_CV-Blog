<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class TwigRendererFactory.
 */
class TwigRendererFactory
{
    /**
     * TwigRendererFactory __invoke.
     *
     * @param ContainerInterface $container
     *
     * @return RendererInterface
     */
    public function __invoke(ContainerInterface $container): RendererInterface
    {
        $loader = new FilesystemLoader($container->get('default_views.path'));
        $twig = new Environment($loader);
        if ($container->get('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }
        $renderer = new TwigRenderer($loader, $twig);
        $renderer->addViewPath('site', $container->get('site_views.path'));
        $renderer->addViewPath('blog', $container->get('blog_views.path'));

        return $renderer;
    }
}
