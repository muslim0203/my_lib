<?php

namespace Tests\Feature;

use App\Core\Services\Pay\Helpers\QrCodeImage;
use Tests\TestCase;

class QrCodeImageTest extends TestCase
{
    public function test_qr_code_is_an_svg_data_uri_that_needs_no_imagick(): void
    {
        $this->assertFalse(extension_loaded('imagick'), 'Bu test imagick bo\'lmagan muhitni tekshiradi.');

        $uri = QrCodeImage::dataUri('https://my.click.uz/services/pay?service_id=38191&amount=25000');

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $uri);

        $svg = base64_decode(substr($uri, strlen('data:image/svg+xml;base64,')), true);

        $this->assertNotFalse($svg);
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('width="300"', $svg);
    }

    public function test_different_content_yields_different_images(): void
    {
        $this->assertNotSame(
            QrCodeImage::dataUri('https://example.test/a'),
            QrCodeImage::dataUri('https://example.test/b')
        );
    }
}
