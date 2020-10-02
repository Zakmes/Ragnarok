<?php

namespace App\Domains\Users\DTO;

use App\Domains\Users\Http\Requests\LockFormRequest;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class LockReasonObject
 *
 * @package App\Domains\Users\DTO
 */
class LockReasonObject extends DataTransferObject
{
    public string $comment;

    /**
     * Method for mapping the request data to data attributes.
     *
     * @param  LockFormRequest $request The request instance that holds all the request information.
     * @return static
     */
    public static function fromRequest(LockFormRequest $request): self
    {
        return new self(['comment' => $request->get('reason')]);
    }
}
