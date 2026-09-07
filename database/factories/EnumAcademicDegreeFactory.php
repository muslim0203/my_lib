<?php

namespace Database\Factories;

use App\Models\Enums\EnumAcademicDegree;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumAcademicDegree>
 */
class EnumAcademicDegreeFactory extends Factory
{
    protected $model = EnumAcademicDegree::class;

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
            'enabled' => true,
        ];
    }
}
