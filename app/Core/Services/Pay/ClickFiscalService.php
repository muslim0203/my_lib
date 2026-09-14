<?php

namespace App\Core\Services\Pay;

use App\Models\Orders\ClickPayment;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Click Merchant API orqali to'lov uchun fiskal chek (OFD) ma'lumotlarini yuboradi.
 *
 * Chek yuborish to'lov oqimini hech qachon to'xtatmaydi: har qanday xato
 * faqat logga yoziladi va false qaytariladi. Click complete callback'iga
 * muvaffaqiyat javobi baribir qaytadi, chunki pul allaqachon o'tgan.
 */
class ClickFiscalService
{
    /**
     * Fiskal ma'lumot yuborish uchun barcha kerakli sozlamalar mavjudmi.
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        $fiscal = (array)config('click.fiscal', []);

        if (empty($fiscal['enabled'])) {
            return false;
        }

        foreach (['inn', 'spic', 'package_code', 'item_name'] as $key) {
            if ((string)($fiscal[$key] ?? '') === '') {
                return false;
            }
        }

        foreach (['merchant_user_id', 'secret_key', 'service_id'] as $key) {
            if ((string)config('click.' . $key) === '') {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ClickPayment $clickPayment
     * @return bool
     */
    public function sendReceipt(ClickPayment $clickPayment): bool
    {
        if (!$this->isConfigured()) {
            Log::warning("Click OFD: fiskal sozlamalar to'liq emas, chek yuborilmadi.", [
                'click_payment_id' => $clickPayment->getId(),
            ]);

            return false;
        }

        if (empty($clickPayment->getClickPaydocId())) {
            Log::warning("Click OFD: click_paydoc_id yo'q, chek yuborilmadi.", [
                'click_payment_id' => $clickPayment->getId(),
            ]);

            return false;
        }

        $payload = $this->buildPayload($clickPayment);

        try {
            $response = Http::withHeaders(['Auth' => $this->authHeader()])
                ->acceptJson()
                ->timeout(10)
                ->post(config('click.api_url') . '/merchant/payment/ofd_data', $payload);
        } catch (ConnectionException $e) {
            Log::error('Click OFD: ulanish xatosi.', [
                'click_payment_id' => $clickPayment->getId(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        $body = $response->json();
        $errorCode = (int)($body['error_code'] ?? -1);

        if (!$response->successful() || $errorCode !== 0) {
            Log::error('Click OFD: chek qabul qilinmadi.', [
                'click_payment_id' => $clickPayment->getId(),
                'status' => $response->status(),
                'body' => $body,
            ]);

            return false;
        }

        Log::info('Click OFD: chek yuborildi.', [
            'click_payment_id' => $clickPayment->getId(),
            'payment_id' => $clickPayment->getClickPaydocId(),
        ]);

        return true;
    }

    /**
     * Click hujjatidagi ofd_data so'rov tanasi. Narxlar tiyinda.
     *
     * @param ClickPayment $clickPayment
     * @return array
     */
    public function buildPayload(ClickPayment $clickPayment): array
    {
        $fiscal = (array)config('click.fiscal');
        $totalTiyin = self::toTiyin($clickPayment->getAmount());

        return [
            'service_id' => (int)config('click.service_id'),
            'payment_id' => (int)$clickPayment->getClickPaydocId(),
            'items' => [
                [
                    'Name' => (string)$fiscal['item_name'],
                    'SPIC' => (string)$fiscal['spic'],
                    'PackageCode' => (string)$fiscal['package_code'],
                    'Price' => $totalTiyin,
                    'Amount' => 1,
                    'Total' => $totalTiyin,
                    'VATPercent' => (int)$fiscal['vat_percent'],
                    'CommissionInfo' => [
                        'TIN' => (string)$fiscal['inn'],
                    ],
                ],
            ],
            'received_ecash' => $totalTiyin,
            'received_cash' => 0,
            'received_card' => 0,
        ];
    }

    /**
     * Merchant API sarlavhasi: merchant_user_id:sha1(timestamp + secret_key):timestamp
     *
     * @param int|null $timestamp
     * @return string
     */
    public function authHeader(?int $timestamp = null): string
    {
        $timestamp = $timestamp ?? time();
        $digest = sha1($timestamp . (string)config('click.secret_key'));

        return config('click.merchant_user_id') . ':' . $digest . ':' . $timestamp;
    }

    /**
     * So'mdagi summani tiyinga o'tkazadi (float xatolarisiz).
     *
     * @param string|int|float $amount
     * @return int
     */
    public static function toTiyin(string|int|float $amount): int
    {
        return (int)round(((float)$amount) * 100);
    }
}
