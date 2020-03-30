<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use MyWebsite\Utils\RedirectResponse;
use MyWebsite\Utils\Session\FlashService;
use MyWebsite\Utils\Validator\Validator;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ForgottenPasswordController.
 */
class ForgottenPasswordController extends AbstractController
{
    /**
     * ForgottenPasswordController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return RedirectResponse|string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        if ('GET' === $request->getMethod()) {
            return $this->renderer->renderView('auth/password');
        }
        $validator = $this->getValidator($request);
        $params = $request->getParsedBody();
        if ($validator->isValid()) {
            $user = $this->userRepository->findUser($params['email']);
            $token = $this->userRepository->resetPassword($user->getId());
            $this->mailer->sendResetPassMail(
                $user->getEmail(),
                ['id' => $user->getId(), 'token' => $token]
            );
            (new FlashService($this->session))
                ->success('Un email vous a été envoyé');

            return new RedirectResponse($request->getUri()->getPath());
        }
        $errors = $validator->getErrors();

        return $this->renderer->renderView('auth/password', compact('errors'));
    }

    /**
     * Validator instance with defined rules
     *
     * @param ServerRequestInterface $request
     *
     * @return Validator
     */
    public function getValidator(ServerRequestInterface $request): Validator
    {
        $params = $request->getParsedBody();

        return (new Validator($params))
            ->required('email')
            ->exists('email')
            ->email('email');
    }
}
