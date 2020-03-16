<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Validator;

/**
 * Class ValidationError.
 */
class ValidationError
{
    /**
     * Error key
     *
     * @var string
     */
    protected $key;

    /**
     * Error rules
     *
     * @var string
     */
    protected $rule;

    /**
     * Error attributes
     *
     * @var array
     */
    protected $attributes;

    /**
     * Validation error messages
     *
     * @var array
     */
    protected $messages = [
        'required' => 'Le champs %s est requis.',
        'minLength' => 'Le champs %s doit contenir plus de %d caractères.',
        'maxLength' => 'Le champs %s doit contenir moins de %d caractères.',
        'betweenLength' => 'Le champs %s doit contenir entre %d et %d caractères.',
        'dateTime' => 'Le champs %s doit être une date valide (%s).',
        'slug' => 'Le champs %s n\'est pas un slug valide.',
        'filetype' => 'Le champs %s n\'est pas au format valide (%s).',
        'uploaded' => 'Vous devez uploader un fichier.',
    ];

    /**
     * ValidationError constructor.
     *
     * @param string $key
     * @param string $rule
     * @param array  $attributes
     */
    public function __construct(string $key, string $rule, array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    /**
     * Convert Error Object to string error message
     *
     * @return string
     */
    public function __toString(): string
    {
        $params = array_merge(
            [$this->messages[$this->rule], $this->key],
            $this->attributes
        );

        return (string) call_user_func_array('sprintf', $params);
    }
}
