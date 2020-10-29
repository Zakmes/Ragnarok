<?php

namespace App\Domains\Users\Http\Requests;

use App\Domains\Users\Enums\GroupEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateFormRequest
 *
 * @package App\Domains\Users\Http\Requests
 */
class UpdateFormRequest extends FormRequest
{
    /**
     * Request validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'userGroup' => ['required', 'string', 'max:255', Rule::in([GroupEnum::WEBMASTER, GroupEnum::DEVELOPER, GroupEnum::USER])],
            'password' => ['sometimes', 'nullable', 'min:8', 'string', 'confirmed'],
        ];
    }
}
