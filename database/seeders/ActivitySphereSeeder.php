<?php

namespace Database\Seeders;

use App\Models\Enums\EnumActivityType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySphereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name_oz' => 'Nashriyot',
                'name_uz' => 'Нашриёт',
                'name_ru' => 'Издатель',
            ]
        ];

        foreach ($data as $d) {
            if (EnumActivityType::query()->where('name_oz', $d['name_oz'])->exists()) {
                continue;
            }
            $model = new EnumActivityType();
            $model->fill($d);
            $model->save();
        }
    }
}
