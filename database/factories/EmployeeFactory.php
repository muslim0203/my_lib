<?php

namespace Database\Factories;

use App\Models\Users\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Random\RandomException;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws RandomException
     */
    public function definition(): array
    {
        return [
            'first_name'      => fake()->firstName(),
            'last_name'       => fake()->lastName(),
            'middle_name'     => fake()->lastName(),
            'birth_date'      => fake()->date(),
            'pin_fl'          => random_int(1111111111111111, 99999999999999999),
            'passport'        => 'AB' . random_int(1000000, 9999999),
            'gender'          => 'm',
            'current_address' => fake()->address()
        ];
    }
}
