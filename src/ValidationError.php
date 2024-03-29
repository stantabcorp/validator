<?php

namespace Stantabcorp\Validator;

class ValidationError
{

    private string $key;
    private string $rule;
    private array $attributes;

    private array $messages = [
        ValidationRules::NOT_NULL => "The field %s cannot be null",
        ValidationRules::REQUIRED => "The field %s is required",
        ValidationRules::NOT_EMPTY => "The field %s cannot be empty",
        ValidationRules::LENGTH => "The field %s must contain more than %s characters and less than %s.",
        ValidationRules::DATE_TIME => "The field %s must be a valid date (Format: %s)",
        ValidationRules::MATCH => "The field %s must be equal to the value of %s",
        ValidationRules::EQUAL => "The field %s must be equal to %s",
        ValidationRules::ARRAY => "The field %s must be an array",
        ValidationRules::LIST => "The field %s must be a list",
        ValidationRules::BOOLEAN => "The field %s must be a boolean",
        ValidationRules::BETWEEN => "The field %s must contain between %d and %d characters",
        ValidationRules::PATTERN => "The field %s must follow the following pattern %s",
        ValidationRules::CUSTOM => "%s",
    ];

    /**
     * @param string $key
     * @param string $rule
     * @param array  $attributes
     */
    public function __construct(string $key, string $rule, array $attributes)
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        if ($this->rule == ValidationRules::CUSTOM) {
            return sprintf($this->messages[$this->rule], $this->attributes[0]);
        }
        return sprintf($this->messages[$this->rule], $this->key, ...$this->attributes);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getRule(): string
    {
        return $this->rule;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

}