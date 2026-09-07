<?php

namespace Database\Seeders;

use App\Models\Notifications\Enums\EnumNotificationMessage;
use App\Models\Notifications\Enums\EnumNotificationType;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!EnumNotificationType::query()->where('id', 1)->exists()) {
            EnumNotificationType::factory()
                ->count(4)
                ->sequence(
                    [
                        'id' => 1,
                        'name_oz' => "Yangi muaalif sifatida qo'shilish so'rovi",
                        'name_uz' => "Янги муаалиф сифатида қўшилиш сўрови",
                        'name_ru' => "Просьба присоединиться в качестве нового автора",
                    ],
                    [
                        'id' => 2,
                        'name_oz' => "Yangi mahsulotlarni sotuv uchun chiqarish so'rovlari",
                        'name_uz' => "Янги маҳсулотларни сотув учун чиқариш сўровлари",
                        'name_ru' => "Запросы на выпуск новых продуктов в продажу",
                    ],
                    [
                        'id' => 3,
                        'name_oz' => "Yangi muaalif sifatida qo'shilish so'rovini rad etish",
                        'name_uz' => "Янги муаалиф сифатида қўшилиш сўровини рад этиш",
                        'name_ru' => "Отклонить запрос на присоединение в качестве нового автора",
                    ],
                    [
                        'id' => 4,
                        'name_oz' => "Yangi muaalif sifatida qo'shilish so'rovini tasdiqlash",
                        'name_uz' => "Янги муаалиф сифатида қўшилиш сўровини тасдиқлаш",
                        'name_ru' => "Подтвердите заявку на присоединение в качестве нового автора",
                    ]
                )
                ->create();

            EnumNotificationMessage::factory()
                ->count(6)
                ->sequence(
                    [
                        'notification_type_id' => 1,
                        'message_oz' => "Tizimda muallif sifatida qo'shilish uchun quydagi :id bo'yicha so'rov yuborildi.",
                        'message_uz' => "Тизимда муаллиф сифатида қўшилиш учун қуйдаги :id бўйича сўров юборилди.",
                        'message_ru' => "Был сделан запрос на то, чтобы следующий :id выступал в качестве автора в системе.",
                    ],
                    [
                        'notification_type_id' => 3,
                        'message_oz' => "Tizimda muallif sifatida qo'shilish uchun quydagi :id bo'yicha so'rov rad etildi.",
                        'message_uz' => "Тизимда муаллиф сифатида қўшилиш учун қуйдаги :id бўйича сўров рад этилди",
                        'message_ru' => "Запрос на следующий :id в качестве автора в системе был отклонен.",
                    ],
                    [
                        'notification_type_id' => 4,
                        'message_oz' => "Tizimda muallif sifatida qo'shilish uchun quydagi :id bo'yicha so'rov tasdiqlandi.",
                        'message_uz' => "Тизимда муаллиф сифатида қўшилиш учун қуйдаги :id бўйича сўров тасдиқланди",
                        'message_ru' => "Запрос на присоединение к системе в качестве автора следующего :id одобрен.",
                    ],
                    [
                        'notification_type_id' => 2,
                        'message_oz' => "Tizimga yangi maxsulotlarni sotuvga chiqarish uchun quydagi :id raqami bo'yicha so'rov yuborildi.",
                        'message_uz' => "Тизимга янги махсулотларни сотувга чиқариш учун қуйдаги :id рақами бўйича сўров юборилди.",
                        'message_ru' => "В систему отправлен запрос на запуск нового продукта по следующему номеру :id.",
                    ],
                    [
                        'notification_type_id' => 2,
                        'message_oz' => "Tizimga yangi maxsulotlarni sotuvga chiqarish uchun quydagi :id raqamli so'rov rad etildi.",
                        'message_uz' => "Тизимга янги махсулотларни сотувга чиқариш учун қуйдаги :id рақамли сўров рад этилди.",
                        'message_ru' => "Следующий числовой запрос :id на выпуск новых продуктов в систему был отклонен.",
                    ],
                    [
                        'notification_type_id' => 2,
                        'message_oz' => "Tizimga yangi maxsulotlarni sotuvga chiqarish uchun quydagi :id raqamli so'rov tasdiqlandi.",
                        'message_uz' => "Тизимга янги махсулотларни сотувга чиқариш учун қуйдаги :id рақамли сўров тасдиқланди.",
                        'message_ru' => "Следующий цифровой запрос :id был одобрен для выпуска новых продуктов в систему.",
                    ],
                )
                ->create();
        }
    }
}
