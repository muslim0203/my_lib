<?php

namespace Database\Factories;

use App\Models\Enums\EnumProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumProductStatus>
 */
class ProductStatusFactory extends Factory
{
    protected $model = EnumProductStatus::class;

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
