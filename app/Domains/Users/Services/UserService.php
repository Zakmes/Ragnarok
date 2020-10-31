<?php

namespace App\Domains\Users\Services;

use App\Domains\Users\Enums\GroupEnum;
use App\Domains\Users\Events\UserCreated;
use App\Domains\Users\Events\UserDeleted;
use App\Domains\Users\Events\UserRestored;
use App\Domains\Users\Events\UserUpdated;
use App\Support\Services\BaseService;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

/**
 * Class UserService
 *
 * @package App\Domains\Users\Services
 */
class UserService extends BaseService
{
    /**
     * UserService constructor
     *
     * @param  User $userModel The user model instance that will be used by the service class.
     * @return void
     */
    public function __construct(User $userModel)
    {
        $this->model = $userModel;
    }

    /**
     * Method for updating user account details in the application.
     *
     * @param  User  $user              The resource entity from the given user.
     * @param  array $userDataObject    The given data transfer object that handles the array of information.
     * @return bool
     */
    public function update(User $user, array $userDataObject): bool
    {
        if ($user->update($userDataObject) && $user->isNot(auth()->user())) {
            event(new UserUpdated($user));
            return true;
        }

        return false;
    }

    /**
     * Method for getting the users out of the database.
     *
     * @param  string|null    $filter   The applied filter for getting the users.
     * @param  int            $perPage  The amount of records u want to display per page.
     * @param  array|string[] $columns  The columns u want to display in your overview view.
     * @return Paginator
     */
    public function getUsersBasedOnFilter(?string $filter = null, int $perPage = 15, array $columns = ['*']): Paginator
    {
        $query = $this->model->newQuery();

        $query->when($filter === GroupEnum::USER, static function (Builder $query): Builder {
            return $query->users();
        })->when($filter === GroupEnum::WEBMASTER, static function (Builder $query): Builder {
            return $query->webmasters();
        })->when($filter === GroupEnum::DEVELOPER, static function (Builder $query): Builder {
            return $query->developers();
        })->when($filter === 'deleted', static function (Builder $query): Builder {
            return $query->onlyTrashed();
        });

        return $query->paginate($perPage, $columns);
    }

    /**
     * Method for indication that the given user is signed up for deletion.
     *
     * @param  User $user The given resource entity from the user.
     * @return User
     *
     * @throws \Exception
     */
    public function markUser(User $user): User
    {
        if (auth()->user()->is(auth()->user())) {
            $user->delete();
            event(new UserDeleted($user));
        }

        return $user;
    }

    /**
     * Method for signing out of the user deletion system.
     *
     * @param  int $user The unique identifier in the resource from the user.
     * @return User
     */
    public function restoreUser(int $user): User
    {
        return DB::transaction(function () use ($user): User {
            $user = $this->model->withTrashed()->find($user);
            $user->restore();

            event(new UserRestored($user));

            return $user;
        });
    }

    /**
     * Method for getting all the users in the application that are marked for deletion.
     *
     * @return Collection
     */
    public function getUsersForDeletion(): Collection
    {
        return $this->model->onlyTrashed()->whereDate('deleted_at', '<', now()->subWeek(2))->get();
    }

    /**
     * Merthod for permanently deleting a user in the application.
     *
     * @param  User $user The resource entity from the given user.
     * @return bool|null
     */
    public function deleteUser(User $user)
    {
        return $user->forceDelete();
    }

    /**
     * The array of user groups in the application.
     *
     * @return array
     */
    public function getUserGroupArray(): array
    {
        return GroupEnum::USERGROUPS;
    }

    /**
     * Method for registering new users in the application.
     *
     * @todo Implement password reset when an admin has created an new user account.
     *
     * @param  array        $userInformation The information array that is needed for registering the user.
     * @param  string|null  $role            The permission group that needs to be attached to the user.
     * @return User
     */
    public function registerUser(array $userInformation, ?string $role): User
    {
        $user = DB::transaction(function () use ($userInformation, $role): User {
            $user = $this->model->create($userInformation);
            $user->syncRoles($role);

            return $user;
        });

        event(new UserCreated($user));

        return $user;
    }

    /**
     * Method for getting all the information that is needed for the kiosk dashboard.
     *
     * @return SupportCollection
     */
    public function getDashboardInfo(): SupportCollection
    {
        return collect([
            'users' => $this->model->orderBy('id', 'DESC')->limit(5)->get(),
            'todayCount' => $this->model->whereDate('created_at', now()->today())->count(),
            'totalCount' => $this->count(),
        ]);
    }
}
