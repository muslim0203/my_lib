<?php

namespace Database\Seeders;

use App\Models\Enums\EnumProductGenre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductGenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productGenres = [
            [
                'name_oz' => 'Fantasika',
                'name_uz' => 'Фантастика',
                'name_ru' => 'Фантастика',
            ],
            [
                'name_oz' => "Qo'rqinchli",
                'name_uz' => 'Қўрқинчли',
                'name_ru' => 'Ужасный',
            ],
            [
                'name_oz' => "Jinoyat",
                'name_uz' => 'Жиноят',
                'name_ru' => 'Преступление',
            ],
            [
                'name_oz' => 'Tarixiy',
                'name_uz' => 'Тарихий',
                'name_ru' => 'Исторический'
            ],
            [
                'name_oz' => 'Ilm fan',
                'name_uz' => 'Илм фан',
                'name_ru' => 'Наука есть наука'
            ],
            [
                'name_oz' => 'Diniy',
                'name_uz' => 'Диний',
                'name_ru' => 'Религиозный'
            ],
            [
                'name_oz' => 'Ilmiy',
                'name_uz' => 'Илмий',
                'name_ru' => 'Научный'
            ],
            [
                'name_oz' => 'Biznes',
                'name_uz' => 'Бизнес',
                'name_ru' => 'Бизнес'
            ],
            [
                'name_oz' => 'Detektiv',
                'name_uz' => 'Детектив',
                'name_ru' => 'Детектив'
            ],
            [
                'name_oz' => 'Siyosiy',
                'name_uz' => 'Сиёсий',
                'name_ru' => 'Политический'
            ],
            [
                'name_oz' => 'Biografik',
                'name_uz' => 'Биографик',
                'name_ru' => 'Биографический'
            ],
            [
                'name_oz' => 'Psixologiya',
                'name_uz' => 'Психология',
                'name_ru' => 'Психология'
            ]
        ];

        foreach ($productGenres as $productGenre) {
            if (EnumProductGenre::query()->where('name_oz', $productGenre['name_oz'])->exists()) {
                continue;
            }
            $model = new EnumProductGenre();
            $model->fill($productGenre);
            $model->save();
        }
    }
}
