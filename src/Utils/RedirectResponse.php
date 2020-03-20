<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use GuzzleHttp\Psr7\Response;

/**
 * Class RedirectResponse.
 */
class RedirectResponse extends Response
{
    /**
     * RedirectResponse constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        parent::__construct(301, ['Location' => $url]);
    }
}
