<?php

namespace Database\Factories;

use App\Models\Enums\EnumProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumProductType>
 */
class ProductTypeFactory extends Factory
{
    protected $model = EnumProductType::class;

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
