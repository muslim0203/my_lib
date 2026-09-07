<?php

namespace Database\Factories;

use App\Models\Enums\EnumFileType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumFileType>
 */
class EnumFileTypeFactory extends Factory
{
    protected $model = EnumFileType::class;

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
