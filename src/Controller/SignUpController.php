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
 * Class SignUpController.
 */
class SignUpController extends AbstractController
{
    /**
     * SignUpController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return RedirectResponse|string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'GET') {
            return $this->renderer->renderView('account/signup');
        }
        $params = $request->getParsedBody();
        $validator = (new Validator($params))
            ->required(
                'firstName',
                'lastName',
                'email',
                'password',
                'password_confirm'
            )
            ->length('firstName', 3)
            ->length('lastName', 3)
            ->email('email')
            ->confirm('password')
            ->length('password', 4)
            ->unique('email')
            ->unique('password');
        if ($validator->isValid()) {
            $userParams = [
                'firstName' => $params['firstName'],
                'lastName' => $params['lastName'],
                'email' => $params['email'],
                'password' => password_hash($params['password'], PASSWORD_DEFAULT),
            ];
            $this->userRepository->insertUser($userParams);
            $user = $this->userRepository->findUser($userParams['email']);
            $this->auth->setUser($user);
            (new FlashService($this->session))
                ->success('Votre compte a bien été créé');

            return new RedirectResponse(
                $this->router->generateUri('account.profile', [])
            );
        }
        $errors = $validator->getErrors();

        return $this->renderer->renderView(
            'account/signup',
            [
                'errors' => $errors,
                'user' => [
                    'firstName' => $params['firstName'],
                    'lastName' => $params['lastName'],
                    'email' => $params['email'],
                ],
            ]
        );
    }
}
