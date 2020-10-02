<?php

namespace App\Domains\Announcements\Models\Scopes;

use App\Domains\Announcements\Enums\AnnouncementAreaEnum;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait AnnouncementScope
 *
 * @package App\Domains\Announcements\Models\Scopes
 */
trait AnnouncementScope
{
    /**
     * Method for getting all the enabled announcements from the database table.
     *
     * @param  Builder $query The eloquent query builder instance.
     * @return Builder
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->whereEnabled(true);
    }

    /**
     * Method for getting all the announcements for a specific area.
     *
     * @param  Builder      $query The eloquent query builder instance.
     * @param  string|null  $area  The area name where the announcement will be placed.
     * @return Builder
     */
    public function scopeForArea(Builder $query, ?string $area): Builder
    {
        return $query->where(static function (Builder $query) use ($area): void {
            $query->whereArea($area)->orWhereNull('area');
        });
    }

    /**
     * The scope that determines whether the announcement is in the correct timeframe or not.
     *
     * @param  Builder $query The eloquent query builder instance
     * @return Builder
     */
    public function scopeInTimeFrame(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->where(function ($query) {
                $query->whereNull('starts_at')
                    ->whereNull('ends_at');
            })->orWhere(function ($query) {
                $query->whereNotNull('starts_at')
                    ->whereNotNull('ends_at')
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now());
            })->orWhere(function ($query) {
                $query->whereNotNull('starts_at')
                    ->whereNull('ends_at')
                    ->where('starts_at', '<=', now());
            })->orWhere(function ($query) {
                $query->whereNull('starts_at')
                    ->whereNotNull('ends_at')
                    ->where('ends_at', '>=', now());
            });
        });
    }

    /**
     * Method for getting all the unread announcements from the user out of the database.
     *
     * @param  Builder $query The query builder instance
     * @return Builder
     */
    public function scopeUnreads(Builder $query): Builder
    {
        $area = (auth()->user()->canAccessKiosk()) ? AnnouncementAreaEnum::BACKEND : AnnouncementAreaEnum::FRONTEND;

        return $query->enabled()->forArea($area)->whereDoesntHave('announcementReads', function (Builder $query) {
            return $query->where('user_id', auth()->id());
        });
    }
}
