<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Validator;

use MyWebsite\Repository\PostRepository;

/**
 * Class Validator.
 */
class Validator
{
    /**
     * Error's parameters
     *
     * @var array
     */
    protected $params;

    /**
     * Form validation errors
     *
     * @var array
     */
    protected $errors;

    /**
     * Validator constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Verify fields present in table and not empty
     *
     * @param string ...$keys
     *
     * @return Validator
     */
    public function required(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'required');
            }
        }

        return $this;
    }

    /**
     * Check if Validator contains no errors
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Add an Error
     *
     * @param string $key
     * @param string $rule
     * @param array  $attributes
     *
     * @return void
     */
    public function addError(string $key, string $rule, array $attributes = []): void
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attributes);
    }

    /**
     * Retrieve Errors
     *
     * @return ValidationError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Retrieve a value
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }

        return null;
    }

    /**
     * Length validation rules
     *
     * @param string   $key
     * @param int|null $min
     * @param int|null $max
     *
     * @return $this
     */
    public function length(string $key, ?int $min, ?int $max = null): self
    {
        $value = $this->getValue($key);
        $length = mb_strlen($value);
        if (!is_null($min)
            && !is_null($max)
            && ($length < $min || $length > $max)
        ) {
            $this->addError($key, 'betweenLength', [$min, $max]);

            return $this;
        }
        if (!is_null($min)
            && $length < $min
        ) {
            $this->addError($key, 'minLength', [$min]);

            return $this;
        }
        if (!is_null($max)
            && $length > $max
        ) {
            $this->addError($key, 'maxLength', [$max]);
        }

        return $this;
    }
}
