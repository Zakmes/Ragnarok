<?php

namespace Database\Factories;

use App\Domains\Users\Enums\GroupEnum;
use App\Domains\Users\Models\Role;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ModelFactory
 *
 * @package Database\Factories
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'name' => GroupEnum::USER,
            'description' => $this->faker->text,
            'guard_name' => 'web',
        ];
    }
}
