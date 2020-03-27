<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Exception;
use MyWebsite\Utils\RedirectResponse;
use MyWebsite\Utils\Session\FlashService;
use MyWebsite\Utils\Validator\Validator;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ResetPasswordController.
 */
class ResetPasswordController extends AbstractController
{
    /**
     * ResetPasswordController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return RedirectResponse|string
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $user = $this->userRepository->findUserById($request->getAttribute('id'));
        $passwordResetAt = $user->setPasswordResetAt($user->getPasswordResetAt());
        if ($user->getPasswordReset() !== null
            && $user->getPasswordReset() === $request->getAttribute('token')
            && time() - $passwordResetAt->getTimestamp() < 4200
        ) {
            if ($request->getMethod() === 'GET') {
                return $this->renderer->renderView('auth/reset');
            }
            $validator = $this->getValidator($request);
            $params = $request->getParsedBody();
            if ($validator->isValid()) {
                $this->userRepository->updatePassword(
                    $user->getId(),
                    $params['password']
                );
                (new FlashService($this->session))
                    ->success('Votre Password a bien été changé');

                return new RedirectResponse(
                    $this->router->generateUri('auth.login', [])
                );
            }
            $errors = $validator->getErrors();

            return $this->renderer->renderView('auth/reset', compact('errors'));
        }
        (new FlashService($this->session))
            ->success('Token invalid');

        return new RedirectResponse($this->router->generateUri('auth.password', []));
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
            ->length('password', 4)
            ->unique('password')
            ->confirm('password');
    }
}
