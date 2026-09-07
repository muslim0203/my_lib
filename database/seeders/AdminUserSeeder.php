<?php

namespace Database\Seeders;

use App\Core\Helpers\Transaction;
use App\Models\Users\Employee;
use App\Models\Users\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Transaction $transaction): void
    {
        $transaction->wrap(function () {
            $employee = Employee::factory(1)->create();

            foreach (self::getUsersList() as $item) {
                if (User::query()->where('username', $item['username'])->exists()) {
                    continue;
                }
                $user = new User();
                $user->fill($item);
                $user->setEmployeeId($employee->value('id'));
                $user->save();
            }
        });
    }

    public function getUsersList(): array
    {
        return [
            [
                'username' => 'admin',
                'password' => 'Qwerty123$',
                'email' => fake()->email(),
            ]
        ];
    }
}
