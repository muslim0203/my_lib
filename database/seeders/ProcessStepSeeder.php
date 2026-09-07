<?php

namespace Database\Seeders;

use App\Models\Steps\ProcessStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcessStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        foreach (self::getList() as $item) {
            if (ProcessStep::query()->where('code_name', $item['code_name'])->exists()) {
                continue;
            }
            $requestType = new ProcessStep();
            $requestType->fill($item);
            $requestType->save();
        }
    }
    public function getList(): array
    {
        return [
            [
                'id' => 1,
                'name_oz' => "Yangi muaalif sifatida qo'shilish so'rovini moderator ko'rib chiqish jarayonida",
                'name_uz' => 'Янги муаалиф сифатида қўшилиш сўровини модератор кўриб чиқиш жараёнида',
                'name_ru' => 'Ваш запрос на присоединение в качестве нового автора рассматривается модератором.',
                'code_name' => 'process_add_new_author',
            ],
            [
                'id' => 2,
                'name_oz' => "Yangi muaalif sifatida qo'shilish so'rovi rad etildi",
                'name_uz' => 'Янги муаалиф сифатида қўшилиш сўрови рад этилди',
                'name_ru' => 'Запрос на присоединение, поскольку новый автор отклонен',
                'code_name' => 'revoked_add_new_author',
            ],
            [
                'id' => 3,
                'name_oz' => "Yangi muaalif sifatida qo'shilish so'rovi tasdiqlandi",
                'name_uz' => 'Янги муаалиф сифатида қўшилиш сўрови тасдиқланди',
                'name_ru' => 'Запрос на присоединение в качестве нового автора одобрен',
                'code_name' => 'confirmed_add_new_author',
            ],
            [
                'id' => 4,
                'name_oz' => "Yangi maxsulotni sotuvga chiqarish so'rovi",
                'name_uz' => 'Янги махсулотни сотувга чиқариш сўрови',
                'name_ru' => 'Запрос на запуск нового продукта',
                'code_name' => 'new_product_request',
            ],
            [
                'id' => 5,
                'name_oz' => "Yangi maxsulotni sotuvga chiqarish so'rovi rad etildi",
                'name_uz' => 'Янги махсулотни сотувга чиқариш сўрови рад этилди',
                'name_ru' => 'Запрос на запуск нового продукта был отклонен',
                'code_name' => 'rejected_add_new_product_request',
            ],
            [
                'id' => 6,
                'name_oz' => "Yangi maxsulotni sotuvga chiqarish so'rovi tasdiqlandi",
                'name_uz' => 'Янги махсулотни сотувга чиқариш сўрови тасдиқланди',
                'name_ru' => 'Запрос на запуск нового продукта одобрен',
                'code_name' => 'confirmed_add_new_product_request',
            ],
            [
                'id' => 7,
                'name_oz' => "Yangi tashkilotni qo'shilish so'rovini moderator ko'rib chiqish jarayonida",
                'name_uz' => 'Янги ташкилотни қўшилиш сўровини модератор кўриб чиқиш жараёнида',
                'name_ru' => 'Заявка на вступление в новую организацию рассматривается модератором',
                'code_name' => 'new_authority_request',
            ],
            [
                'id' => 8,
                'name_oz' => "Yangi tashkilotni qo'shish so'rovi rad etildi",
                'name_uz' => 'Янги ташкилотни қўшиш сўрови рад этилди',
                'name_ru' => 'Запрос на добавление новой организации отклонен.',
                'code_name' => 'rejected_add_new_authority_request',
            ],
            [
                'id' => 9,
                'name_oz' => "Yangi tashkilotni qo'shish so'rovi tasdiqlandi",
                'name_uz' => 'Янги ташкилотни қўшиш сўрови тасдиқланди',
                'name_ru' => 'Запрос на добавление новой организации одобрен',
                'code_name' => 'confirmed_add_new_authority_request',
            ]
        ];
    }
}
