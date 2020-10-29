<?php

namespace App\Domains\Activity\Models;

use Database\Factories\ActivityFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

/**
 * Class Activity
 *
 * @package App\Domains\Activity\Models
 */
class Activity extends SpatieActivity
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ActivityFactory::new();
    }
}
