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
            'smtp.googlemail.com',
            465,
            'ssl'
        ))
            ->setUsername('wizbhoo.dev@gmail.com')
            ->setPassword('0703Or1onAPEldrazil6$')
        ;

        return new Swift_Mailer($transport);
    }
}
