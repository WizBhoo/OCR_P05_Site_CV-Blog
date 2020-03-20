<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Psr\Container\ContainerInterface;
use Swift_Mailer;
use Swift_SendmailTransport;

/**
 * Class SwiftMailerFactory.
 */
class SwiftMailerFactory
{
    /**
     * SwiftMailerFactory __invoke.
     *
     * @param ContainerInterface $container
     *
     * @return Swift_Mailer
     */
    public function __invoke(ContainerInterface $container): Swift_Mailer
    {
        $transport = new Swift_SendmailTransport();

        return new Swift_Mailer($transport);
    }
}
