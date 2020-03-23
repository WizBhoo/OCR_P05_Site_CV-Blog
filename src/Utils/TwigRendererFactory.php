<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
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
        $twig = new Environment($loader, ['debug' => true]);
        $twig->addExtension(new DebugExtension());
        if ($container->get('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }
        $renderer = new TwigRenderer($twig);
        $renderer->addViewPath('site', $container->get('site_views.path'));
        $renderer->addViewPath('blog', $container->get('blog_views.path'));
        $renderer->addViewPath('auth', $container->get('auth_views.path'));
        $renderer->addViewPath('account', $container->get('account_views.path'));
        $renderer->addViewPath('admin', $container->get('admin_views.path'));

        return $renderer;
    }
}
