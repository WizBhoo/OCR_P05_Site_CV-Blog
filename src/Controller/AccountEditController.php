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
 * Class AccountEditController.
 */
class AccountEditController extends AbstractController
{
    /**
     * AccountEditController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return RedirectResponse|string
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $user = $this->auth->getUser();
        $validator = $this->getValidator($request);
        if ($validator->isValid()) {
            $userParams = $this->getParams($request);
            $this->userRepository->updateUser($user->getId(), $userParams);
            (new FlashService($this->session))
                ->success('Votre compte a bien été mis à jour');

            return new RedirectResponse($request->getUri()->getPath());
        }
        $errors = $validator->getErrors();

        return $this->renderer->renderView(
            'account/profile',
            compact('user', 'errors')
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
            ->confirm('password')
            ->unique('password')
            ->required('firstName', 'lastName');
    }

    /**
     * Retrieve userParams to be post for update
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function getParams(ServerRequestInterface $request): array
    {
        $user = $this->auth->getUser();
        $params = $request->getParsedBody();
        $userParams = [
            'firstName' => $params['firstName'],
            'lastName' => strtoupper($params['lastName']),
        ];
        if (!empty($params['password'])) {
            $userParams['password'] = password_hash(
                $params['password'],
                PASSWORD_DEFAULT
            );
        } else {
            $userParams['password'] = $user->getPassword();
        }

        return $userParams;
    }
}
