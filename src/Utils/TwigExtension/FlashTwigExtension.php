<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\TwigExtension;

use MyWebsite\Utils\Session\FlashService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FlashTwigExtension.
 */
class FlashTwigExtension extends AbstractExtension
{
    /**
     * A FlashService Instance.
     *
     * @var FlashService
     */
    protected $flashService;

    /**
     * FlashTwigExtension constructor.
     *
     * @param FlashService $flashService
     */
    public function __construct(FlashService $flashService)
    {
        $this->flashService = $flashService;
    }

    /**
     * FlashTwigExtension getFunction.
     *
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', [$this, 'getFlash']),
        ];
    }

    /**
     * FlashTwigExtension getFlash.
     *
     * @param string $type
     *
     * @return string|null
     */
    public function getFlash(string $type): ?string
    {
        return $this->flashService->get($type);
    }
}
