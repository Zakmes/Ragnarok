<?php

namespace App\Domains\Users\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class MatchRecoveryCode
 *
 * @package App\Domains\Users\Rules
 */
class MatchRecoveryCode implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string   $attribute The name from the attribute field.
     * @param  mixed    $value     The value from the attribute.
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return auth()->user()->recoveryCodeIsValid($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('Invalid recover code given.');
    }
}
