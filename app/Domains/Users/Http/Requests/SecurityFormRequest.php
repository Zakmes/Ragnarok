<?php

namespace App\Domains\Users\Http\Requests;

use ActivismeBe\ValidationRules\Rules\MatchUserPassword;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SecurityFormRequest
 *
 * @package App\Domains\Users\Http\Requests
 */
class SecurityFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'currentPassword' => ['required', 'string', 'min:8', new MatchUserPassword($this->user())],
            'password' => ['required', 'min:8', 'string', 'confirmed'],
        ];
    }
}
