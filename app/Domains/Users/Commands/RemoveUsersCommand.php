<?php

namespace App\Domains\Users\Commands;

use App\Domains\Users\Actions\DeleteAction;
use App\Domains\Users\Enums\DeleteType;
use App\Domains\Users\Services\UserService;
use Illuminate\Console\Command;

/**
 * Class RemoveUsersCommand
 *
 * @package App\Domains\Users\Commands
 */
class RemoveUsersCommand extends Command
{
    private UserService $userService;
    private DeleteAction $userDeleteAction;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kiosk:users-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanent deletion for users that are marked +2 weeks marked for deletion.';

    public function __construct(UserService $userService, DeleteAction $userDeleteAction)
    {
        parent::__construct();

        $this->userService = $userService;
        $this->userDeleteAction = $userDeleteAction;
    }

    public function handle(): void
    {
        $this->info('[' . now() . ']: Starting to remove users that are marked for deletion.');

        foreach ($users = $this->userService->getUsersForDeletion() as $user) {
            $this->userDeleteAction->onQueue('users')->execute($user, DeleteType::DELETE);
        }

        $this->info('[' . now() . ']: Removed successfully ' . count($users) . ' users');
    }
}
