<?php

namespace Database\Seeders;

use App\Models\Enums\EnumProductType;
use Illuminate\Database\Seeder;

class ProductTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getList() as $item) {
            if (EnumProductType::query()->where('name_oz', $item['name_oz'])->exists()) {
                continue;
            }
            $model = new EnumProductType();
            $model->fill($item);
            $model->save();
        }
    }

    /**
     * @return array[]
     */
    public function getList(): array
    {
        return [
            [
                'name_oz' => 'Ovozli kitob',
                'name_uz' => 'Овозли китоб',
                'name_ru' => 'Аудиокнига'
            ],
            [
                'name_oz' => 'Matnli kitob',
                'name_uz' => 'Матнли китоб',
                'name_ru' => 'Текст книга'
            ],
            [
                'name_oz' => 'Video darslik',
                'name_uz' => 'Видео дарслик',
                'name_ru' => 'Видеоурок'
            ]
        ];
    }
}
