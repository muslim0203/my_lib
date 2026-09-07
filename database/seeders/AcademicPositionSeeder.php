<?php

namespace Database\Seeders;

use App\Models\Enums\EnumAcademicPosition;
use Illuminate\Database\Seeder;

class AcademicPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->list() as $item) {
            if (!EnumAcademicPosition::query()
                ->where('name_oz', $item['name_oz'])
                ->where('name_uz', $item['name_uz'])
                ->where('name_ru', $item['name_ru'])
                ->exists()){
                $model = new EnumAcademicPosition();
                $model->fill($item);
                $model->save();
            }
        }
    }

    public function list()
    {
        return [
            [
                'name_oz' => 'Dotsent',
                'name_uz' => 'Доцент',
                'name_ru' => 'Доцент'
            ],
            [
                'name_oz' => 'Katta ilmiy xodim',
                'name_uz' => 'Катта илмий ходим',
                'name_ru' => 'Старший научный сотрудник'
            ],
            [
                'name_oz' => 'Bosh ilmiy xodim',
                'name_uz' => 'Бош илмий ходим',
                'name_ru' => 'Главный научный сотрудник'
            ],
            [
                'name_oz' => 'Professor',
                'name_uz' => 'Профессор',
                'name_ru' => 'Профессор'
            ],
            [
                'name_oz' => 'Akademik',
                'name_uz' => 'Академик',
                'name_ru' => 'Академик'
            ],
        ];
    }
}
