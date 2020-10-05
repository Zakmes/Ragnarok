<?php

namespace Database\Factories;

use App\Domains\Roles\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ModelFactory
 *
 * @package Database\Factories
 */
class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'guard_name' => 'web',
        ];
    }
}
