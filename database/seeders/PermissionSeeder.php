<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::list() as $key => $item) {
            if (Permission::query()->where('name', $item['name'])->exists()) {
                continue;
            }
            Permission::create($item);
        }
    }

    /**
     * @return array[]
     */
    public static function list(): array
    {
        return [
            [
                'name'       => 'user',
                'guard_name' => 'mail'
            ],
            [
                'name'       => 'user',
                'guard_name' => 'google'
            ],
            [
                'name'       => 'user',
                'guard_name' => 'phone'
            ],
            [
                'name'       => 'merchant',
                'guard_name' => 'mail'
            ],
            [
                'name'       => 'merchant',
                'guard_name' => 'google'
            ],
            [
                'name'       => 'merchant',
                'guard_name' => 'phone'
            ],
            [
                'name'       => 'admin',
                'guard_name' => 'web'
            ]
        ];
    }
}
