<?php

namespace App\Domains\Api\Services;

use App\Domains\Api\Models\PersonalAccessToken;
use App\Domains\Api\Notifications\TokenRevokedNotification;
use App\Support\Services\BaseService;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Class TokenService
 *
 * @package App\Domains\Api\Services
 */
class TokenService extends BaseService
{
    /**
     * TokenService constructor.
     *
     * @param PersonalAccessToken $personalAccessToken The database model for the personal access tokens (API).
     */
    public function __construct(PersonalAccessToken $personalAccessToken)
    {
        $this->model = $personalAccessToken;

        if ($this->apiModuleDisabled()) {
            throw new RuntimeException('The API module is not enabled in the configuration.');
        }
    }

    /**
     * Determine whether the API module is enabled.
     *
     * @return bool
     */
    private function apiModuleDisabled(): bool
    {
        return ! config('spoon.modules.api-tokens');
    }

    /**
     * Get all the personal access tokens out of the database in a paginated way.
     *
     * @param  string|null    $filter  The filter group u want to apply to the overview.
     * @param  int            $perPage The amount of records u want to display per page.
     * @param  array|string[] $columns The column names u want to use in your output.
     * @return Paginator
     */
    public function getAllPersonalAccessTokens(?string $filter = null, int $perPage = 15, array $columns = ['*']): Paginator
    {
        $personalAccessTokens = $this->model->newQuery();

        $personalAccessTokens->when($filter === 'revoked', static function (Builder $personalAccessTokens) use ($perPage, $columns): Paginator {
            return $personalAccessTokens->onlyTrashed()->paginate($perPage, $columns);
        });

        return $personalAccessTokens->paginate($perPage, $columns);
    }

    /**
     * Method for getting all the tokens for a user in the paginated form.
     *
     * @param  User           $user    The database entity from the given user.
     * @param  int            $perPage The amount of records u want to display per page.
     * @param  array|string[] $columns The column names u want to use in your view or method.
     * @return Paginator
     */
    public function tokensForUser(User $user, int $perPage = 15, array $columns = ['*']): Paginator
    {
        return $user->tokens()->paginate($perPage, $columns);
    }

    /**
     * Method for revoking an API access token.
     *
     * @param PersonalAccessToken $accessToken The database entity from the access token.
     * @return bool
     *
     * @throws \Exception
     */
    public function revokeToken(PersonalAccessToken $accessToken): bool
    {
        $authenticatedUser = auth()->user();

        return DB::transaction(function () use ($accessToken, $authenticatedUser): ?bool {
            if ($authenticatedUser->isNot($accessToken->tokenable)) {
                $accessToken->tokenable->notify(new TokenRevokedNotification($authenticatedUser, $accessToken));
                return $accessToken->delete();
            }

            return $accessToken->forceDelete();
        });
    }

    /**
     * Method for restoring an personal access token in the application.
     *
     * @param  PersonalAccessToken $accessToken The unique identifier from the access token in the application.
     * @return bool|null
     */
    public function restoreToken(PersonalAccessToken $accessToken): ?bool
    {
        return $accessToken->restore();
    }

    /**
     * Get all the information that is needed for the kiosk dashboard.
     *
     * @return Collection
     */
    public function getDashBoardInfo(): Collection
    {
        return collect([
            'countTotal' => $this->count(),
            'countToday' => $this->model->whereDate('created_at', now()->today())->count(),
        ]);
    }
}
