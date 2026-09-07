<?php

namespace Database\Factories;

use App\Models\Enums\EnumCategories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumCategories>
 */
class CategoryFactory extends Factory
{
    protected $model = EnumCategories::class;

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
        ];
    }
}
