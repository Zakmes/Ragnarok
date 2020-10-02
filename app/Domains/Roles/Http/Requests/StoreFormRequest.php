<?php

namespace App\Domains\Roles\Http\Requests;

use App\Domains\Users\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreFormRequest
 *
 * @package App\Domains\Roles\Http\Requests
 */
class StoreFormRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized for performing the handling.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Role::class);
    }

    /**
     * The form request validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:roles'],
            'description' => ['required', 'string'],
        ];
    }
}
