<?php

namespace Stantabcorp\Validator;

use Adbar\Dot;
use DateTime;

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
            $errors[] = (string)$error;
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
        foreach ($keys as $key) {
            if ($this->getValue($key) == null) {
                $this->addError($key, ValidationRules::NOT_NULL);
            }
        }
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
        foreach ($keys as $key) {
            if (is_null($this->getValue($key)) && empty($this->getValue($key))) {
                $this->addError($key, ValidationRules::NOT_EMPTY);
            }
        }
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
        $dot = new Dot($this->body);
        foreach ($keys as $key) {
            if (!$dot->has($key)) {
                $this->addError($key, ValidationRules::REQUIRED);
            }
        }
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
        $value = $this->getValue($key);

        if ($value == null) {
            $this->addError($key, ValidationRules::LENGTH, $min, $max);
            return $this;
        }

        if (strlen($value) < $min) {
            $this->addError($key, ValidationRules::LENGTH, $min, $max);
            return $this;
        }

        if ($max != null && strlen($value) > $max) {
            $this->addError($key, ValidationRules::LENGTH, $min, $max);
            return $this;
        }

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
        if ($this->getValue($key) == null) {
            $this->addError($key, ValidationRules::DATE_TIME, $format);
            return $this;
        }

        // https://github.com/lefuturiste/validator/blob/9e4e653597437acb277b48167ebd6acbaef65a8f/src/Validator.php#L134
        $date = DateTime::createFromFormat($format, $this->getValue($key));
        $errors = DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date == false) {
            $this->addError($key, ValidationRules::DATE_TIME, $format);
        }

        return $this;
    }

    /**
     * Test if the keys are valid slugs
     *
     * @param string ...$keys
     * @return $this
     */
    public function slug(string ...$keys): self
    {
        foreach ($keys as $key) {
            /** @var TYPE_NAME $this */
            $this->patternMatch($key, "/^[a-z0-9_]+(-[a-z0-9_]+)*$/");
        }

        return $this;
    }

    /**
     * Test if the keys are valid urls
     *
     * @param string ...$keys
     * @return $this
     */
    public function url(string ...$keys): self
    {
        foreach ($keys as $key) {
            /** @var TYPE_NAME $this */
            $this->patternMatch($key, "/((\w+:\/\/)[-a-zA-Z0-9:@;?&=\/%\+\.\*!'\(\),\$_\{\}\^~\[\]`#|]+)/");
        }

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
        if ($this->getValue($key) !== $expected) {
            $this->addError($key, ValidationRules::MATCH, $expected);
        }

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
        if ($this->getValue($key) !== $this->getValue($secondKey)) {
            $this->addError($key, ValidationRules::EQUAL, $secondKey);
        }

        return $this;
    }

    /**
     * Test if the key is a valid email address
     *
     * @param string ...$keys
     * @return $this
     */
    public function email(string ...$keys): self
    {
        foreach ($keys as $key) {
            /** @var TYPE_NAME $this */
            $this->patternMatch($key, "/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/");
        }

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
        foreach ($keys as $key) {
            if (!is_array($key)) {
                $this->addError($key, ValidationRules::ARRAY);
            }
        }

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
        foreach ($keys as $key) {
            /** @var TYPE_NAME $this */
            $this->patternMatch($key, "/^-?[0-9]+$/");
        }

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
        foreach ($keys as $key) {
            /** @var TYPE_NAME $this */
            $this->patternMatch($key, "/^-?[0-9]+((\.|,)[0-9]+)?$/");
        }

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
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (!(
                ($value === false)
                || ($value === true)
                || ($value === 'false')
                || ($value === 'true')
                || ($value === 0)
                || ($value === 1)
                || ($value === '0')
                || ($value === '1')
            )) { // https://github.com/lefuturiste/validator/blob/9e4e653597437acb277b48167ebd6acbaef65a8f/src/Validator.php#L313
                $this->addError($key, ValidationRules::BOOLEAN);
            }
        }

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
        if ($strict) {
            if ($this->getValue($key) > $max || $this->getValue($key) < $min) {
                $this->addError($key, ValidationRules::BETWEEN, $min, $max);
            }
        } else {
            if ($this->getValue($key) >= $max || $this->getValue($key) <= $min) {
                $this->addError($key, ValidationRules::BETWEEN, $min, $max);
            }
        }
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
        if ($this->getValue($key) != null && !preg_match($pattern, $this->getValue($key))) {
            $this->addError($key, ValidationRules::PATTERN, $pattern);
        }
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
        foreach ($keys as $key) {
            /** @var TYPE_NAME $this */
            $this->patternMatch($key, "/^[a-zA-Z0-9]+$/");
        }
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
        $customValidator = new CustomValidator();
        $function($this->getValue($key), $customValidator);

        foreach ($customValidator->getErrors() as $error) {
            $this->addError($key, ValidationRules::CUSTOM, $error);
        }

        return $this;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getValue(string $key, $default = null)
    {
        $dot = new Dot($this->body);
        return $dot->get($key, $default);
    }

    /**
     * @param string $key
     * @param string $rule
     * @param ...$attributes
     * @return void
     */
    private function addError(string $key, string $rule, ...$attributes)
    {
        $this->errors[] = new ValidationError($key, $rule, $attributes);
    }
}