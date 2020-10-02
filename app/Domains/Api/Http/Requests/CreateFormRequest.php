<?php

namespace App\Domains\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateFormRequest
 *
 * @package App\Domains\Http\Requests
 */
class CreateFormRequest extends FormRequest
{
    /**
     * The request validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return ['tokenName' => ['required', 'string', 'max:255']];
    }
}
