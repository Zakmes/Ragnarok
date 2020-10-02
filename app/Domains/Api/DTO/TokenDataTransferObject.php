<?php

namespace App\Domains\Api\DTO;

use App\Domains\Api\Http\Requests\CreateFormRequest;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class TokenDataTransferObject
 *
 * @package App\Domains\Api\DTO
 */
class TokenDataTransferObject extends DataTransferObject
{
    public string $name;

    /**
     * Method for mapping the form request to database fields.
     *
     * @param  CreateFormRequest $request The request instance that contains all the request data.
     * @return static
     */
    public static function fromRequest(CreateFormRequest $request): self
    {
        return new static(['name' => $request->get('tokenName')]);
    }
}
