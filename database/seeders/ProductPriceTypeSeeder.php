<?php

namespace Database\Seeders;

use App\Models\Products\ProductPriceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductPriceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getList() as $item) {
            if (ProductPriceType::query()->where('name_oz', $item['name_oz'])->exists()) {
                continue;
            }
            $productPriceType = new ProductPriceType();
            $productPriceType->fill($item);
            $productPriceType->save();
        }
    }

    /**
     * @return array[]
     */
    public function getList(): array
    {
        return [
            [
                'name_oz' => 'Bepul',
                'name_uz' => 'Бепул',
                'name_ru' => 'Бесплатно',
                'content_oz' => 'Sizni mahsulotlaringiz bepul foydalanish uchun saytga chiqariladi',
                'content_uz' => 'Сизни маҳсулотларингиз бепул фойдаланиш учун сайтга чиқарилади',
                'content_ru' => 'Ваша продукция будет размещена на сайте для бесплатного использования.'
            ],
            [
                'name_oz' => 'Asosiy',
                'name_uz' => 'Асосий',
                'name_ru' => 'Базовая',
                'content_oz' => 'Har bir sotuvdan 25% ulush',
                'content_uz' => 'Ҳар бир сотувдан 25% улуш',
                'content_ru' => '25% роялти c каждой продажи'
            ],
            [
                'name_oz' => 'Eksklyuziv',
                'name_uz' => 'Эксклюзив',
                'name_ru' => 'Эксклюзивный',
                'content_oz' => 'Har bir sotuvdan 35% ulush',
                'content_uz' => 'Ҳар бир сотувдан 35% улуш',
                'content_ru' => '35% роялти c каждой продажи'
            ]
        ];
    }
}
