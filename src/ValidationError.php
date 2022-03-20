<?php

namespace Stantabcorp\Validator;

class ValidationError
{

    private string $key;
    private string $rule;
    private array $attributes;

    /**
     * @param string $key
     * @param string $rule
     * @param array $attributes
     */
    public function __construct(string $key, string $rule, array $attributes)
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
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