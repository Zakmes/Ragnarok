<?php

namespace App\Domains\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class InformationFormRequest
 *
 * @package App\Domains\Users\Http\Requests
 */
class InformationFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'unique:users,email,' . $this->user()->id],
        ];
    }
}
