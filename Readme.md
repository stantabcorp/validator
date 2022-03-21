# Validator

A simple PHP validation library

[![PHP Composer](https://github.com/stantabcorp/validator/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/stantabcorp/validator/actions/workflows/php.yml)

## Installation

```bash
composer require stantabcorp/validator
```

## Using the library

```php
$validator = new \Stantabcorp\Validator\Validator(["array" => ["to" => "validate"]]); // Init the library providing an array to validate.
$validator->required("array.to"); // Test if the key `to` in the array `array` is present.
$validator->array("array"); // Test if the key `array` is an array.

$validator->isValid(); // Return a boolean is the array is valid or not.

$validator->getErrors(); // Return a list of string containing the error messages.
```

### Custom validation

```php
$validator->customValidation("array", function (\Stantabcorp\Validator\CustomValidator $customValidator) {
    $customValidator->getKey(); // The key (`array` in this case)
    $customValidator->getValue(); // The associated value
    
    // Mark the kay as invalid and add an error message.
    $customValidator->addError("This is an error message");
});
```

## Testing

```bash
composer run-script test
```