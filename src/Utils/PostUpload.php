<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

/**
 * Class PostUpload.
 */
class PostUpload extends Upload
{
    /**
     * Upload's path
     *
     * @var string
     */
    protected $path = 'img/blog';

    /**
     * Thumb's format
     *
     * @var array
     */
    protected $formats = [
        'thumb' => [320, 180],
    ];
}
