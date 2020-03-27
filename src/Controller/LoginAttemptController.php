<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Utils\RedirectResponse;
use MyWebsite\Utils\Session\FlashService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class LoginAttemptController.
 */
class LoginAttemptController extends AbstractController
{
    /**
     * LoginAttemptController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface|string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();
        $user = $this->auth->login($params['email'], $params['password']);
        if ($user && true === $user->getAccountStatus()) {
            if ('user' === $user->getAccountType()) {
                $path = $this->router->generateUri('site.home', []);

                return new RedirectResponse($path);
            }
            $path = $this->session->get('auth.redirect')
                ?: $this->router->generateUri('admin.dashboard', []);
            $this->session->delete('auth.redirect');

            return new RedirectResponse($path);
        }
        if ($user && false === $user->getAccountStatus()) {
            (new FlashService($this->session))
                ->error(
                    'Votre compte n\'est pas encore activé ! Patientez SVP'
                );

            return $this->router->redirect('auth.login');
        }
        (new FlashService($this->session))
            ->error('Identifiant ou mot de passe incorrect');

        return $this->router->redirect('auth.login');
    }
}
