<?php

namespace App\Domains\Users\DTO;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class UserEntityObject
 *
 * @package App\Domains\Users\DTO
 */
class UserEntityObject extends DataTransferObject
{
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $user_group;
    public ?string $role;
    public ?string $password;

    /**
     * Method for mapping the request data to data attributes.
     *
     * @param  Request $request The request instance that holds all the request information.
     * @return static
     */
    public static function fromRequest(Request $request): self
    {
        return new self([
            'firstName' => $request->get('firstName'),
            'lastName' => $request->get('lastName'),
            'email' => $request->get('email'),
            'role' => $request->get('role'),
            'user_group' => $request->get('userGroup'),
            'password' => $request->get('password'),
        ]);
    }
}
