<?php

namespace Database\Seeders;

use App\Models\Reports\ReportType;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'id' => 1,
                'title_oz' => "Ko'p sotib olgan foydalanuvchilar",
                'title_uz' => "Ko'p sotib olgan foydalanuvchilar",
                'title_ru' => "Ko'p sotib olgan foydalanuvchilar",
                'created_at' => now(),
                'is_show_front' => true
            ],
            [
                'id' => 2,
                'title_oz' => "Ko'p sotilgan kitoblar",
                'title_uz' => "Ko'p sotilgan kitoblar",
                'title_ru' => "Ko'p sotilgan kitoblar",
                'created_at' => now(),
                'is_show_front' => true
            ],
            [
                'id' => 3,
                'title_oz' => "Ko'p foyda keltirgan kitoblar",
                'title_uz' => "Ko'p foyda keltirgan kitoblar",
                'title_ru' => "Ko'p foyda keltirgan kitoblar",
                'created_at' => now(),
                'is_show_front' => true
            ],
            [
                'id' => 4,
                'title_oz' => "Kam sotilgan kitoblar",
                'title_uz' => "Kam sotilgan kitoblar",
                'title_ru' => "Kam sotilgan kitoblar",
                'created_at' => now(),
                'is_show_front' => true
            ],
            [
                'id' => 5,
                'title_oz' => "Kam foyda keltirgan kitoblar",
                'title_uz' => "Kam foyda keltirgan kitoblar",
                'title_ru' => "Kam foyda keltirgan kitoblar",
                'created_at' => now(),
                'is_show_front' => true
            ],
            [
                'id' => 6,
                'title_oz' => "Xarid qilinmagan kitoblar",
                'title_uz' => "Xarid qilinmagan kitoblar",
                'title_ru' => "Xarid qilinmagan kitoblar",
                'created_at' => now(),
                'is_show_front' => true
            ],
            [
                'id' => 7,
                'title_oz' => "Bepul kitoblar",
                'title_uz' => "Bepul kitoblar",
                'title_ru' => "Bepul kitoblar",
                'created_at' => now(),
                'is_show_front' => true
            ]
        ];

        foreach ($values as $value) {
            if (ReportType::query()->where('id', $value['id'])->exists()) {
                continue;
            }

            ReportType::query()
                ->firstOrCreate($value);
        }
    }
}
