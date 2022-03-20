<?php

namespace Stantabcorp\Validator;

class CustomValidator
{

    private array $errors;

    public function addError(string $message)
    {
        $this->errors[] = $message;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

}