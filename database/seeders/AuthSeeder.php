<?php

namespace Database\Seeders;

use App\Domains\Users\Enums\GroupEnum;
use App\Support\Database\DisableForeignKeys;
use App\Support\Database\TruncateTable;
use App\User;
use Illuminate\Database\Seeder;

/**
 * Class AuthSeeder
 *
 * @package Database\Seeders
 */
class AuthSeeder extends Seeder
{
    use DisableForeignKeys;
    use TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(['users', 'password_resets']);

        foreach ($this->getUserGroups() as $key => $group) {
            User::create([
                'user_group' => $group,
                'firstname' => $group,
                'lastName' => 'user',
                'email' => $group . '@domain.tld',
                'password' => 'password',
                'email_verified_at' => now(),
            ]);
        }

        $this->enableForeignKeys();
    }

    private function getUserGroups(): array
    {
        return [GroupEnum::WEBMASTER, GroupEnum::DEVELOPER, GroupEnum::USER];
    }
}
