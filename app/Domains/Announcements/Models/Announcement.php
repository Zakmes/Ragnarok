<?php

namespace App\Domains\Announcements\Models;

use App\Domains\Announcements\Models\Relations\AnnouncementStatusRelation;
use App\Domains\Announcements\Models\Scopes\AnnouncementScope;
use App\Domains\Users\Models\Relations\CreatorRelation;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Announcement
 *
 * @package App\Domains\Announcements\Models
 */
class Announcement extends Model
{
    use AnnouncementScope;
    use AnnouncementStatusRelation;
    use CreatorRelation;

    /**
     * The mass-assignable fields in the database.
     *
     * @var string[]
     */
    protected $fillable = ['title', 'area', 'type', 'message', 'enabled', 'starts_at', 'ends_at'];

    /**
     * The date fields from the database table.
     *
     * @var string[]
     */
    protected $dates = ['starts_at', 'ends_at'];

    /**
     * The data fields casts for the database table.
     *
     * @var string[]
     */
    protected $casts = ['enabled' => 'boolean'];
}
