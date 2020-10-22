<?php

namespace App\Domains\Users\Http\Requests;

use ActivismeBe\ValidationRules\Rules\MatchUserPassword;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TwoFactorDisableRequest
 *
 * @package App\Domains\Users\Http\Requests
 */
class TwoFactorDisableRequest extends FormRequest
{
    /**
     * The validation rules for the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return ['current-password' => ['required', new MatchUserPassword($this->user())]];
    }
}
