<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Auth;

use MyWebsite\Utils\RendererInterface;
use Swift_Mailer;
use Swift_Message;

/**
 * Class PasswordResetMailer.
 */
class PasswordResetMailer
{
    /**
     * A Swift_Mailer Instance
     *
     * @var Swift_Mailer
     */
    protected $mailer;
    /**
     * A RendererInterface Injection
     *
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * PasswordResetMailer constructor.
     *
     * @param Swift_Mailer      $mailer
     * @param RendererInterface $renderer
     */
    public function __construct(Swift_Mailer $mailer, RendererInterface $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    /**
     * Setup message to be sent by email
     *
     * @param string $to
     * @param array  $params
     *
     * @return void
     */
    public function sendMail(string $to, array $params): void
    {
        $message = (
            new Swift_Message(
                'Réinitialisation de votre mot de passe',
                $this->renderer->renderView(
                    'email/emailPassword',
                    $params
                ),
                'text/html'
            )
        )
            ->setTo($to)
            ->setFrom(['wizbhoo.dev@gmail.com' => 'Adrien PIERRARD']);
        $this->mailer->send($message);
    }
}
