<?php

namespace App\Domains\Roles\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateFormRequest
 *
 * @property array|null                     $permissions
 * @property \App\Domains\Roles\Models\Role $role
 *
 * @package  App\Domains\Roles\Http\Requests
 */
class UpdateFormRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to perform the request or not.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->role);
    }

    /**
     * Method to determine the validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
           'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $this->role->id],
           'description' => ['required', 'string'],
       ];
    }
}
