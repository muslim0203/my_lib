<?php

namespace Database\Factories;

use App\Models\Notifications\Enums\EnumNotificationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumNotificationType>
 */
class NotificationTypeFactory extends Factory
{
    protected $model = EnumNotificationType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
