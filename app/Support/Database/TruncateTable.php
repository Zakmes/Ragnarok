<?php

namespace App\Support\Database;

use Illuminate\Support\Facades\DB;

/**
 * Trait TruncateTable
 *
 * @package App\Support\Database
 */
trait TruncateTable
{
    /**
     * Method for truncating a specific table in the application.
     *
     * @param  string $table The given table name.
     * @return void|bool
     */
    protected function truncate(string $table)
    {
        switch (DB::getDriverName()) {
            case 'mysql':                return DB::table($table)->truncate();
            case 'pgsql':                return DB::statement('TRUNCATE TABLE ' . $table . ' RESTART IDENTITY CASCADE');
            case 'sqlite':case 'sqlsrv': return DB::statement('DELETE FROM' . $table);
        }
    }

    protected function truncateMultiple(array $tables)
    {
        foreach ($tables as $table) {
            $this->truncate($table);
        }
    }
}
