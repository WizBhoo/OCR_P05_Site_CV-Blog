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
        'required' => 'Ce champs est requis.',
        'minLength' => '%s > à %d caractères SVP.',
        'maxLength' => '%s > à %d caractères SVP.',
        'betweenLength' => '%s doit contenir entre %d et %d caractères.',
        'dateTime' => 'Le champs %s doit être une date valide (%s).',
        'slug' => 'Le champs %s n\'est pas un slug valide.',
        'filetype' => 'Le format n\'est pas valide (%s).',
        'uploaded' => 'Vous devez uploader un fichier.',
        'email' => 'Cet email ne semble pas valide.',
        'confirm' => 'Vos mots de passe ne se correspondent pas.',
        'unique' => 'Le champs %s doit être unique.',
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
        if (!array_key_exists($this->rule, $this->messages)) {
            return "Le champs {$this->key} ne correspond pas à la règle {$this->rule}";
        }
        $params = array_merge(
            [$this->messages[$this->rule], $this->key],
            $this->attributes
        );

        return (string) call_user_func_array('sprintf', $params);
    }
}
