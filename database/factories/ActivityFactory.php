<?php

namespace Database\Factories;

use App\Domains\Activity\Models\Activity;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use ReflectionClass;

/**
 * Class ActivityFactory
 *
 * @package Database\Factories
 */
class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'log_name' => 'Users',
            'description' => $this->faker->text,
            'causer_id' => User::factory()->create()->id,
            'causer_type' => 'App\User',
            'subject_type' => 'App\User',
            'subject_id' => User::factory()->create()->id,
            'properties' => [],
        ];
    }
}
