<?php

namespace App\Console\Commands;

use App\Core\Services\Product\ProductsOrderService;
use Illuminate\Console\Command;

/**
 * Qo'lda pul qaytarilganda Xaridorning kitobga kirishini yopish
 * (Xaridorlar ofertasi 6.5-band). Payme orqali qaytarilgan to'lovlar
 * avtomatik yopiladi, bu buyruq boshqa hollar uchun.
 */
class RevokeProductAccess extends Command
{
    protected $signature = 'orders:revoke-access
        {product_id : Mahsulot ID si}
        {customer_id : Xaridor (users.id)}
        {--transaction= : Faqat shu tranzaksiya bo\'yicha yaratilgan orderni yopish}';

    protected $description = 'Xaridorning sotib olingan Mahsulotga kirish huquqini yopadi (pul qaytarilganda)';

    public function handle(ProductsOrderService $productsOrderService): int
    {
        $count = $productsOrderService->revoke(
            (int) $this->argument('product_id'),
            (int) $this->argument('customer_id'),
            $this->option('transaction') !== null ? (string) $this->option('transaction') : null
        );

        if ($count === 0) {
            $this->warn('Faol order topilmadi, hech narsa o\'zgarmadi.');

            return self::FAILURE;
        }

        $this->info("Kirish yopildi: {$count} ta order.");

        return self::SUCCESS;
    }
}
