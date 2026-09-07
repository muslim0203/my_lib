<?php

namespace Database\Seeders;

use App\Models\Enums\EnumRequestType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getList() as $item) {
            if (EnumRequestType::query()->where('name_oz', $item['name_oz'])->exists()) {
                continue;
            }
            $requestType = new EnumRequestType();
            $requestType->fill($item);
            $requestType->save();
        }
    }

    /**
     * @return array[]
     */
    public function getList(): array
    {
        return [
            [
                'name_oz' => "Maxsulotlar so'rovi",
                'name_uz' => "Maxsulotlar so'rovi",
                'name_ru' => "Maxsulotlar so'rovi"
            ],
            [
                'name_oz' => "Mualliflar so'rovi",
                'name_uz' => "Mualliflar so'rovi",
                'name_ru' => "Mualliflar so'rovi"
            ],
            [
                'name_oz' => "Mualliflar tashkilot sifatida so'rovi",
                'name_uz' => "Mualliflar tashkilot sifatida so'rovi",
                'name_ru' => "Mualliflar tashkilot sifatida so'rovi"
            ],

        ];
    }
}
