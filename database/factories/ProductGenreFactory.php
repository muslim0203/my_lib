<?php

namespace Database\Factories;

use App\Models\Enums\EnumProductGenre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumProductGenre>
 */
class ProductGenreFactory extends Factory
{
    protected $model = EnumProductGenre::class;

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
            'name_ru' => fake()->name()
        ];
    }
}
