<?php

namespace Database\Seeders;

use App\Models\Enums\EnumProductStatus;
use Illuminate\Database\Seeder;

class ProductStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productStatuses = [
            [
                'id' => 1,
                'name_oz' => 'Yangi',
                'name_uz' => 'Янги',
                'name_ru' => 'Новый',
                'code' => 'NEW'
            ],
            [
                'id' => 2,
                'name_oz' => 'Bozori chaqqon',
                'name_uz' => 'Бозори чаққон',
                'name_ru' => 'Рынок гибкий',
                'code' => 'BESTSELLER'
            ],
            [
                'id' => 3,
                'name_oz' => 'Eksklyuziv',
                'name_uz' => 'Эксклюзив',
                'name_ru' => 'Эксклюзивный',
                'code' => 'EXCLUSIVE'
            ]
        ];

        foreach ($productStatuses as $productStatus) {
            if (EnumProductStatus::query()->where('id', $productStatus['id'])->exists()) {
                continue;
            }
            $model = new EnumProductStatus();
            $model->fill($productStatus);
            $model->save();
        }
    }
}
