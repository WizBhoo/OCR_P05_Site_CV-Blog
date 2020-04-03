<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Psr\Container\ContainerInterface;
use Swift_Mailer;
use Swift_SmtpTransport;

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
        $transport = (new Swift_SmtpTransport(
            $_ENV['SM_HOST'],
            $_ENV['SM_PORT'],
            $_ENV['SM_ENCRYPT']
        ))
            ->setUsername($_ENV['SM_USER'])
            ->setPassword($_ENV['SM_PASS'])
        ;

        return new Swift_Mailer($transport);
    }
}
