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
     *
     * @return string
     *
     * @throws Exception
     */
    public function csrfInput()
    {
        return sprintf(
            "<input type=\"hidden\" name=\"%s\" value=\"%s\"/>",
            $this->csrfMiddleware->getFormKey(),
            $this->csrfMiddleware->generateToken()
        );
    }
}
