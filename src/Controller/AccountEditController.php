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
        $params = $request->getParsedBody();
        $validator = (new Validator($params))
            ->confirm('password')
            ->required('firstName', 'lastName');
        if ($validator->isValid()) {
            $userParams = [
                'firstName' => $params['firstName'],
                'lastName' => $params['lastName'],
            ];
            if (!empty($params['password'])) {
                $userParams['password'] = password_hash(
                    $params['password'],
                    PASSWORD_DEFAULT
                );
            } else {
                $userParams['password'] = $params['password'];
            }
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
}
