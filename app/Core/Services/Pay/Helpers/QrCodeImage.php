<?php

namespace App\Core\Services\Pay\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * To'lov havolasi uchun QR kod rasmini data-URI ko'rinishida beradi.
 *
 * SVG formati ataylab tanlangan: PNG backend'i `imagick` kengaytmasini
 * talab qiladi, u Railway (Railpack) muhitida yo'q va so'rov 500 bilan
 * yiqilardi. SVG hech qanday kengaytmasiz hosil bo'ladi va <img src>
 * ichida bir xil ko'rinadi.
 */
class QrCodeImage
{
    public const SIZE = 300;

    /**
     * @param string $content
     * @param int $size
     * @return string
     */
    public static function dataUri(string $content, int $size = self::SIZE): string
    {
        $svg = (string)QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($content);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
