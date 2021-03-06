<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Controller;

use Exception;
use MyWebsite\Utils\Session\FlashService;
use MyWebsite\Utils\Validator\Validator;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class SiteController.
 */
class SiteController extends AbstractController
{
    /**
     * SiteController __invoke.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request): string
    {
        $path = $request->getUri()->getPath();
        $slug = $request->getAttribute('slug');
        switch ($path) {
            case '/':
                return $this->home();
            case '/portfolio':
                return $this->portfolio();
            case sprintf('/portfolio/%s', $slug):
                return $this->project($slug);
            case '/contact':
                return $this->contact($request);
            default:
                throw new Exception('Route not found');
        }
    }

    /**
     * Route callable function home.
     *
     * @return string
     */
    public function home(): string
    {
        return $this->renderer->renderView('site/home');
    }

    /**
     * Route callable function portfolio.
     *
     * @return string
     */
    public function portfolio(): string
    {
        return $this->renderer->renderView('site/portfolio');
    }

    /**
     * Route callable function for projects.
     *
     * @param string $slug
     *
     * @return string
     */
    public function project(string $slug): string
    {
        switch ($slug) {
            case 'p1':
                return $this->renderer->renderView(
                    'site/works/project1',
                    ['slug' => $slug]
                );
            case 'p2':
                return $this->renderer->renderView(
                    'site/works/project2',
                    ['slug' => $slug]
                );
            case 'p3':
                return $this->renderer->renderView(
                    'site/works/project3',
                    ['slug' => $slug]
                );
            case 'p4':
                return $this->renderer->renderView(
                    'site/works/project4',
                    ['slug' => $slug]
                );
            case 'p5':
                return $this->renderer->renderView(
                    'site/works/project5',
                    ['slug' => $slug]
                );
            case 'p6':
                return $this->renderer->renderView(
                    'site/works/project6',
                    ['slug' => $slug]
                );
            case 'p7':
                return $this->renderer->renderView(
                    'site/works/project7',
                    ['slug' => $slug]
                );
            case 'p8':
                return $this->renderer->renderView(
                    'site/works/project8',
                    ['slug' => $slug]
                );
            default:
                return $this->error404();
        }
    }

    /**
     * Route callable function contact.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function contact(ServerRequestInterface $request): string
    {
        if ($request->getMethod() === 'GET') {
            return $this->renderer->renderView('site/contact');
        }
        $validator = $this->getValidator($request);
        if ($validator->isValid()) {
            (new FlashService($this->session))
                ->success(
                    'Votre message a bien été envoyé'
                );
            $this->mailer->sendContactMail($request->getParsedBody());

            return $this->renderer->renderView('site/contact');
        }
        (new FlashService($this->session))
            ->error(
                'Vous devez remplire tous les champs pour soumettre votre demande'
            );
        $errors = $validator->getErrors();

        return $this->renderer->renderView(
            'site/contact',
            ['errors' => $errors, 'contact' => $this->getParams($request)]
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
            ->required('name', 'email', 'subject', 'message')
            ->length('name', 5)
            ->email('email')
            ->length('subject', 10, 50)
            ->length('message', 10);
    }

    /**
     * Retrieve contactParams
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function getParams(ServerRequestInterface $request): array
    {
        $params = $request->getParsedBody();

        return [
            'name' => $params['name'],
            'email' => $params['email'],
            'subject' => $params['subject'],
            'message' => $params['message'],
        ];
    }
}
