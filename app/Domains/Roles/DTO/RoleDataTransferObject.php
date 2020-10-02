<?php

namespace App\Domains\Roles\DTO;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class RoleDataTransferObject
 *
 * @package App\Domains\Roles\DTO
 */
class RoleDataTransferObject extends DataTransferObject
{
    public string $name;
    public string $description;
    public ?int $creator_id;

    public static function fromRequest(Request $request): self
    {
        return new static([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'creator_id' => $request->user()->id,
        ]);
    }
}
