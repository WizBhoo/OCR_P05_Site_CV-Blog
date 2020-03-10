<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use Exception;
use MyWebsite\Utils\Middleware\CsrfMiddleware;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class CsrfTwigExtension.
 */
class CsrfTwigExtension extends AbstractExtension
{
    /**
     * A CsrfMiddleware instance
     *
     * @var CsrfMiddleware
     */
    protected $csrfMiddleware;

    /**
     * CsrfTwigExtension constructor.
     *
     * @param CsrfMiddleware $csrfMiddleware
     */
    public function __construct(CsrfMiddleware $csrfMiddleware)
    {
        $this->csrfMiddleware = $csrfMiddleware;
    }

    /**
     * CsrfTwigExtension getFunction.
     *
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'csrf_input',
                [$this, 'csrfInput'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * Generate a csrf input
     *
     * @return string
     *
     * @throws Exception
     */
    public function csrfInput(): string
    {
        return sprintf(
            "<input type=\"hidden\" name=\"%s\" value=\"%s\"/>",
            $this->csrfMiddleware->getFormKey(),
            $this->csrfMiddleware->generateToken()
        );
    }
}
