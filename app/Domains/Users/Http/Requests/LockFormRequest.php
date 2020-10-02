<?php

namespace App\Domains\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LockFormRequest
 *
 * @property \App\User $userEntity The user entity that we ant to lock out in the application.
 * @package  App\Domains\Users\Http\Requests
 */
class LockFormRequest extends FormRequest
{
    /**
     * Determine whether the authenticated user can perform the form request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('lock', $this->userEntity);
    }

    public function rules(): array
    {
        return ['reason' => ['required', 'string']];
    }
}
