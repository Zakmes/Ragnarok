<?php

namespace App\Domains\Users\Models;

use App\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TwoFactorAuthentication
 *
 * @package App\Domains\Users\Models
 */
class TwoFactorAuthentication extends Model
{
    /**
     * The guarded fields for the mass-assignment methods.
     *
     * @return array
     */
    protected $guarded = ['id'];

    /**
     * The field columns that are transmuted to casts.
     *
     * @return array
     */
    protected $casts = ['google2fa_recovery_tokens' => 'array'];

    /**
     * Data relation for the user that is attached to the 2fa security information.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Method for deleting the two factor setting in the application.
     *
     * @return bool
     * @throws Exception
     */
    public function disable(): bool
    {
        return $this->delete();
    }
}
