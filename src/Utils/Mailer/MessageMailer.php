<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Mailer;

use MyWebsite\Utils\RendererInterface;
use Swift_Mailer;
use Swift_Message;

/**
 * Class MessageMailer.
 */
class MessageMailer
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
     * MessageMailer constructor.
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
     * Setup reset password message to be sent by email
     *
     * @param string $to
     * @param array  $params
     *
     * @return void
     */
    public function sendResetPassMail(string $to, array $params): void
    {
        $message = (new Swift_Message('Réinitialisation de votre mot de passe'))
            ->setBody(
                $this->renderer->renderView('email/emailPasswordText', $params)
            )
            ->addPart(
                $this->renderer->renderView('email/emailPasswordHtml', $params),
                'text/html'
            )
            ->setTo($to)
            ->setFrom(['wizbhoo.dev@gmail.com' => 'APi - Site CV'])
        ;
        $this->mailer->send($message);
    }

    /**
     * Setup contact message to be sent by email
     *
     * @param array $params
     *
     * @return void
     */
    public function sendContactMail(array $params): void
    {
        $message = (new Swift_Message('Formulaire de contact'))
            ->setBody(
                $this->renderer->renderView('email/contactText', $params)
            )
            ->addPart(
                $this->renderer->renderView('email/contactHtml', $params),
                'text/html'
            )
            ->setTo(['adrien.pierrard@gmail.com'])
            ->setFrom([$params['email'] => 'Contact Site CV APi'])
        ;
        $this->mailer->send($message);
    }
}
