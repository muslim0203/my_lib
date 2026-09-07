<?php

namespace Database\Seeders;

use App\Models\Enums\EnumProductTag;
use Illuminate\Database\Seeder;

class ProductTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productTags = [
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
                'name_oz' => 'Siyosat',
                'name_uz' => 'Сиёсат',
                'name_ru' => 'Политика'
            ],
            [
                'name_oz' => 'Biografiya',
                'name_uz' => 'Биография',
                'name_ru' => 'Биография'
            ],
            [
                'name_oz' => 'Psixologiya',
                'name_uz' => 'Психология',
                'name_ru' => 'Психология'
            ],
            [
                'name_oz' => 'Diniy',
                'name_uz' => 'Диний',
                'name_ru' => 'Религиозный'
            ],
        ];

        foreach ($productTags as $productTag) {
            if (EnumProductTag::query()->where('name_oz', $productTag['name_oz'])->exists()) {
                continue;
            }
            $model = new EnumProductTag();
            $model->fill($productTag);
            $model->save();
        }

    }
}
