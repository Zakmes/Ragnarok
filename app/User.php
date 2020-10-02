<?php

namespace App;

use App\Domains\Users\Models\Attributes\UserAttributes;
use App\Domains\Users\Models\Methods\GeneralMethods;
use App\Domains\Users\Models\Methods\KioskMethods;
use App\Domains\Users\Models\Scopes\UserGroupScopes;
use App\Support\Modules\HasApiModuleChecks;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @package App
 */
class User extends Authenticatable implements BannableContract
{
    use Notifiable;
    use UserAttributes;
    use KioskMethods;
    use GeneralMethods;
    use HasRoles;
    use UserGroupScopes;
    use Bannable;
    use HasApiTokens;
    use HasApiModuleChecks;
    use SoftDeletes;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'user_group', 'password', 'firstName', 'lastName'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime'];
}
