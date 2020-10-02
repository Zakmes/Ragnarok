<?php

namespace App\Domains\Users\Models\Relations;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait CreatorRelation
 *
 * @package App\Domains\Users\Models\Relations
 */
trait CreatorRelation
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setCreator(User $user): bool
    {
        return $this->creator()->associate($user)->save();
    }
}
