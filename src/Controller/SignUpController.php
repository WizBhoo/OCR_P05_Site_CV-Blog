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
        $validator = $this->getValidator($request);
        $params = $request->getParsedBody();
        if ($validator->isValid()) {
            $userParams = $this->getParams($request);
            $userParams['password'] = password_hash(
                $params['password'],
                PASSWORD_DEFAULT
            );
            $this->userRepository->insertUser($userParams);
            (new FlashService($this->session))
                ->success(
                    'Votre compte a bien été créé, veuillez attendre 
                    de recevoir son activation par mail pour pouvoir vous connecter.'
                );

            return new RedirectResponse(
                $this->router->generateUri('site.home', [])
            );
        }
        $errors = $validator->getErrors();

        return $this->renderer->renderView(
            'account/signup',
            [
                'errors' => $errors,
                'user' => $this->getParams($request),
            ]
        );
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
    }

    /**
     * Retrieve userParams
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function getParams(ServerRequestInterface $request): array
    {
        $params = $request->getParsedBody();

        return [
            'firstName' => $params['firstName'],
            'lastName' => strtoupper($params['lastName']),
            'email' => $params['email'],
        ];
    }
}
