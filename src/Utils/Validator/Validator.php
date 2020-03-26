<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils\Validator;

use MyWebsite\Utils\ConnectDb;

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
     * Constant MIME_TYPES
     *
     * @var array
     */
    protected const MIME_TYPES = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
    ];

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
     * @return Validator
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

    /**
     * Verify if file is well uploaded
     *
     * @param string $key
     *
     * @return Validator
     */
    public function uploaded(string $key): self
    {
        $file = $this->getValue($key);
        if (null === $file || UPLOAD_ERR_OK !== $file->getError()) {
            $this->addError($key, 'uploaded');
        }

        return $this;
    }

    /**
     * Verify if the email is valid
     *
     * @param $key
     *
     * @return Validator
     */
    public function email(string $key): self
    {
        $value = $this->getValue($key);
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError($key, 'email');
        }

        return $this;
    }

    /**
     * Confirm password during account sign up
     *
     * @param string $key
     *
     * @return Validator
     */
    public function confirm(string $key): self
    {
        $value = $this->getValue($key);
        $valueConfirm = $this->getValue(sprintf("%s_confirm", $key));

        if ($valueConfirm !== $value) {
            $this->addError($key, 'confirm');
        }

        return $this;
    }

    /**
     * Verify if a field is unique in User table
     *
     * @param string   $key
     * @param int|null $exclude
     *
     * @return Validator
     */
    public function unique(string $key, ?int $exclude = null): self
    {
        $value = $this->getValue($key);
        $query = "SELECT id FROM User WHERE $key = ?";
        $params = [$value];
        if (null !== $exclude) {
            $query .= " AND id != ?";
            $params[] = $exclude;
        }
        $pdo = ConnectDb::getInstance()->getConnection();
        $statement = $pdo->prepare($query);
        $statement->execute($params);
        if ($statement->fetchColumn() !== false) {
            $this->addError($key, 'unique', [$value]);
        }

        return $this;
    }

    /**
     * Verify if a field exists in User table
     *
     * @param string $key
     *
     * @return Validator
     */
    public function exists(string $key): self
    {
        $value = $this->getValue($key);
        $query = "SELECT id FROM User WHERE $key = ?";
        $pdo = ConnectDb::getInstance()->getConnection();
        $statement = $pdo->prepare($query);
        $statement->execute([$value]);
        if ($statement->fetchColumn() === false) {
            $this->addError($key, 'exists', [$value]);
        }

        return $this;
    }

    /**
     * Verify file's format
     *
     * @param string $key
     * @param array  $extensions
     *
     * @return Validator
     */
    public function extension(string $key, array $extensions): self
    {
        $file = $this->getValue($key);
        if (null !== $file && UPLOAD_ERR_OK === $file->getError()) {
            $type = $file->getClientMediaType();
            $extension = mb_strtolower(
                pathinfo(
                    $file->getClientFilename(),
                    PATHINFO_EXTENSION
                )
            );
            $expectedType = self::MIME_TYPES[$extension] ?? null;
            if (!in_array($extension, $extensions) || $expectedType !== $type) {
                $this->addError($key, 'filetype', [join(',', $extensions)]);
            }
        }

        return $this;
    }
}
