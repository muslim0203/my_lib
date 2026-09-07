<?php

namespace Database\Seeders;

use App\Models\Enums\EnumAcademicDegree;
use Illuminate\Database\Seeder;

class AcademicDegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->list() as $item) {
            if (!EnumAcademicDegree::query()
                ->where('name_oz', $item['name_oz'])
                ->where('name_uz', $item['name_uz'])
                ->where('name_ru', $item['name_ru'])
                ->exists()) {
                $model = new EnumAcademicDegree();
                $model->fill($item);
                $model->save();
            }
        }
    }

    /**
     * @return array[]
     */
    public function list(): array
    {
        return [
            [
                'name_oz' => 'Fan nomzodi',
                'name_uz' => 'Фан номзоди',
                'name_ru' => 'кандидат наук'
            ],
            [
                'name_oz' => 'Falsafa doktori (PHD)',
                'name_uz' => 'Фалсафа доктори (ПҲД)',
                'name_ru' => 'доктор философии (PHD)'
            ],
            [
                'name_oz' => 'Fan doktori',
                'name_uz' => 'Фан доктори',
                'name_ru' => 'доктор наук'
            ],
            [
                'name_oz' => 'Fan doktori (DSC)',
                'name_uz' => 'Фан доктори (DSC)',
                'name_ru' => 'доктор наук (DSC)'
            ]
        ];
    }
}
