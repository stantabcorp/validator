<?php

namespace Stantabcorp\Validator;

use Adbar\Dot;
use Stantabcorp\Validator\Exceptions\KeyNotFoundException;

class Validator
{
    /**
     * The body to validate
     * @var array
     */
    private array $body;

    /**
     * List of errors that occurred during the validation
     * @var ValidationError[]
     */
    private array $errors = [];

    /**
     * @param array $body
     */
    public function __construct(array $body)
    {
        $this->body = $body;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->errors as $error) {
            $errors[] = $error;
        }
        return $errors;
    }

    /**
     * Test if the keys are not null
     *
     * @param string ...$keys
     * @return $this
     */
    public function notNull(string  ...$keys): self
    {
        return $this;
    }

    /**
     * Test if the keys are present (required) and not empty
     *
     * @param string ...$keys
     * @return Validator
     */
    public function requiredAndNotEmpty(string ...$keys): self
    {
        return $this->required(...$keys)->notEmpty(...$keys);
    }

    /**
     * Test if the keys are not empty
     *
     * @param string ...$keys
     * @return $this
     */
    public function notEmpty(string ...$keys): self
    {
        return $this;
    }

    /**
     * Test if the keys are present (required)
     *
     * @param string ...$keys
     * @return $this
     */
    public function required(string ...$keys): self
    {
        return $this;
    }

    /**
     * Test if the key is the correct length
     *
     * @param string $key
     * @param int $min
     * @param int|null $max
     * @return $this
     */
    public function length(string $key, int $min, ?int $max = NULL): self
    {
        return $this;
    }

    /**
     * Test if the key follow the provided format
     *
     * @param string $key
     * @param string $format
     * @return $this
     */
    public function dateTime(string $key, string $format = 'Y-m-d H:i:s'): self
    {
        return $this;
    }

    /**
     * Test if the keys are valid slugs
     *
     * @param string ...$key
     * @return $this
     */
    public function slug(string ...$key): self
    {
        return $this;
    }

    /**
     * Test if the keys are valid urls
     *
     * @param string ...$key
     * @return $this
     */
    public function url(string ...$key): self
    {
        return $this;
    }

    /**
     * Test if the key match the provided regex expression
     *
     * @param string $key
     * @param null|mixed $expected
     * @return $this
     */
    public function match(string $key, $expected): self
    {
        return $this;
    }

    /**
     * Test if the key is equal to another key
     *
     * @param string $key
     * @param string $secondKey
     * @return $this
     */
    public function equal(string $key, string $secondKey): self
    {
        return $this;
    }

    /**
     * Test if the key is a valid email address
     *
     * @param string ...$key
     * @return $this
     */
    public function email(string ...$key): self
    {
        return $this;
    }

    /**
     * Test if the keys are valid arrays
     *
     * @param string ...$keys
     * @return $this
     */
    public function array(string ...$keys): self
    {
        return $this;
    }

    /**
     * Test if the keys are valid integers
     *
     * @param string ...$keys
     * @return $this
     */
    public function integer(string ...$keys): self
    {
        return $this;
    }

    /**
     * Test if the keys are valid float numbers
     *
     * @param string ...$keys
     * @return $this
     */
    public function float(string ...$keys): self
    {
        return $this;
    }

    /**
     * Test if the keys are valid booleans
     *
     * @param string ...$keys
     * @return $this
     */
    public function boolean(string ...$keys): self
    {
        return $this;
    }

    /**
     * Test if the key is between a provided range
     *
     * @param string $key
     * @param int $min
     * @param int $max
     * @param bool $strict
     * @return $this
     */
    public function between(string $key, int $min, int $max, bool $strict = false): self
    {
        return $this;
    }

    /**
     * Test if the key match the provided regex expression
     *
     * @param string $key
     * @param string $pattern
     * @return $this
     */
    public function patternMatch(string $key, string $pattern): self
    {
        return $this;
    }

    /**
     * Test if the key is an alphanumerical value
     *
     * @param string ...$keys
     * @return $this
     */
    public function alphaNumerical(string ...$keys): self
    {
        return $this;
    }

    /**
     * Test the key against a custom validation function
     *
     * @param string $key
     * @param callable $function
     * @return $this
     */
    public function customValidation(string $key, callable $function): self
    {
        return $this;
    }

    /**
     * @param string $key
     * @return mixed|null
     * @throws KeyNotFoundException
     */
    public function getValue(string $key)
    {
        $dot = new Dot($this->body);
        if (!$dot->has($key)) {
            if (ValidationSettings::isThrowError()) {
                throw new KeyNotFoundException();
            } else {
                return null;
            }
        }
        return $dot->get($key);
    }
}