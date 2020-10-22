<?php

namespace App\Domains\Users\Http\Requests;

use App\Domains\Users\Rules\MatchRecoveryCode;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RecoveryTokenFormRequest
 *
 * @package App\Domains\Users\Http\Requests
 */
class RecoveryTokenFormRequest extends FormRequest
{
    /**
     * The validation rules for the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return ['recovery_token' => ['required', new MatchRecoveryCode()]];
    }
}
