<?php

namespace App\Attributes;

use Attribute;
use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MenuTarget implements Validator
{
    public function validate(mixed $value): ValidationResult
    {
        if (! in_array($value, ['_self', '_blank', '_parent', '_top'])) {
            return ValidationResult::invalid('Invalid target');
        }

        return ValidationResult::valid();
    }
}
