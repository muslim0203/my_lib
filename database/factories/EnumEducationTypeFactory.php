<?php

namespace Database\Factories;

use App\Models\Enums\EnumEducationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumEducationType>
 */
class EnumEducationTypeFactory extends Factory
{
    protected $model = EnumEducationType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_uz'    => fake()->name(),
            'name_oz'    => fake()->name(),
            'name_ru'    => fake()->name(),
            'enabled'    => 1,
            'created_at' => now()
        ];
    }
}
