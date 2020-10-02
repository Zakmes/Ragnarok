<?php

namespace App\Domains\Announcements\Models\Relations;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait AnnouncementStatusRelation
 *
 * @package App\Domains\Announcements\Models\Relations
 */
trait AnnouncementStatusRelation
{
    /**
     * Data relation for all the announcements that are read by users in the application.
     *
     * @return BelongsToMany
     */
    public function announcementReads(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'announcement_reads')
            ->withTimestamps();
    }

    public function markAsRead(): void
    {
        $this->announcementReads()->attach(auth()->user()->id);
    }
}
