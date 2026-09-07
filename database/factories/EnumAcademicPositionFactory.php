<?php

namespace Database\Factories;

use App\Models\Enums\EnumAcademicPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumAcademicPosition>
 */
class EnumAcademicPositionFactory extends Factory
{
    protected $model = EnumAcademicPosition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_oz' => fake()->name(),
            'name_uz' => fake()->name(),
            'name_ru' => fake()->name(),
            'enabled' => true
        ];
    }
}
