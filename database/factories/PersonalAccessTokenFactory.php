<?php

namespace Database\Factories;

use App\Domains\Api\Models\PersonalAccessToken;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Class PersonalAccessTokenFactory
 *
 * @package Database\Factories
 */
class PersonalAccessTokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonalAccessToken::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'tokenable_type' => 'App\User',
            'tokenable_id' => User::factory()->create()->id,
            'name' => $this->faker->name,
            'token' => hash('sha256', Str::random(40)),
            'abilities' => "['*']",
        ];
    }

    /**
     * Indicate that the token is revoked.
     *
     * @return Factory
     */
    public function revoked(): Factory
    {
        return $this->state(function (array $attributes): array {
            return ['deleted_at' => now()->subWeek()];
        });
    }
}
