<?php

namespace Database\Seeders;

use App\Models\Enums\EnumEducationType;
use Illuminate\Database\Seeder;

class EducationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        foreach ($this->list() as $item) {
            if (!EnumEducationType::query()
                ->where('name_oz', $item['name_oz'])
                ->where('name_uz', $item['name_uz'])
                ->where('name_ru', $item['name_ru'])
                ->exists()){
                $model = new EnumEducationType();
                $model->fill($item);
                $model->save();
            }
        }

    }
    public function list()
    {
        return [
            [
                'name_oz' => 'Bakalavr',
                'name_uz' => 'Бакалавр',
                'name_ru' => 'Бакалавр',
            ],
            [
                'name_oz' => 'Magistr',
                'name_uz' => 'Магистр',
                'name_ru' => 'Магистр',
            ],
            [
                'name_oz' => 'Doktarant',
                'name_uz' => 'Докторант',
                'name_ru' => 'Докторант',
            ]
        ];
    }
}
