<?php

namespace Database\Factories;

use App\Models\Apps;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Apps>
 */
class AppsFactory extends Factory
{

    protected $model = Apps::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                 'name' => $this->faker->name(),
                 'type' => $this->faker->colorName(),
        ];
    }
}
