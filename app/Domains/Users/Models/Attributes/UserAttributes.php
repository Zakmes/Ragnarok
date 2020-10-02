<?php

namespace App\Domains\Users\Models\Attributes;

use Illuminate\Support\Facades\Hash;

/**
 * Trait UserAttributes
 *
 * @package App\Domains\Users\Models\Attributes
 */
trait UserAttributes
{
    public function getNameAttribute(): string
    {
        return ucwords("{$this->firstName} {$this->lastName}");
    }

    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
