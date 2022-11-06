<?php

namespace App\Attributes;

use Attribute;
use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;
use Ysfkaya\ApiLocalization\Facades\ApiLocalization;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MenuLocale implements Validator
{
    public function validate(mixed $value): ValidationResult
    {
        $availableLocales = ApiLocalization::availableLocales();

        if (! in_array($value, $availableLocales)) {
            return ValidationResult::invalid('Invalid locale');
        }

        return ValidationResult::valid();
    }
}
