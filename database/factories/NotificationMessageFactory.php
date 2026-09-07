<?php

namespace Database\Factories;

use App\Models\Notifications\Enums\EnumNotificationMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnumNotificationMessage>
 */
class NotificationMessageFactory extends Factory
{
    protected $model = EnumNotificationMessage::class;

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
