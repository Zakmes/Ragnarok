<?php

namespace App\Domains\Users\DTO;

use App\Domains\Users\Http\Requests\InformationFormRequest;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class UserInformationObject
 *
 * @package App\Domains\Users\DTO
 */
class UserInformationObject extends DataTransferObject
{
    public string $firstName;
    public string $lastName;
    public string $email;

    public static function fromRequest(InformationFormRequest $request): self
    {
        return new self([
            'firstName' => $request->get('firstName'),
            'lastName' => $request->get('lastName'),
            'email' => $request->get('email'),
        ]);
    }
}
