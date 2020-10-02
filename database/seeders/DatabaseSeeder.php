<?php

namespace Database\Seeders;

use App\Support\Database\TruncateTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    use TruncateTable;

    public function run(): void
    {
        Model::unguard();

        $this->truncate('failed_jobs');

        // Run additional seeders
        $this->call(PermissionSeeder::class);
        $this->call(AuthSeeder::class);
        $this->call(AnnouncementSeeder::class);

        Model::reguard();
    }
}
