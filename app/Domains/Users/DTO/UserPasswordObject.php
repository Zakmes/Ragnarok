<?php

namespace App\Domains\Users\DTO;

use App\Domains\Users\Http\Requests\SecurityFormRequest;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class UserPasswordObject
 *
 * @package App\Domains\Users\DTO
 */
class UserPasswordObject extends DataTransferObject
{
    public string $password;

    public static function fromRequest(SecurityFormRequest $request): self
    {
        return new static(['password' => $request->get('password')]);
    }
}
