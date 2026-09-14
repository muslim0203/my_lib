<?php

namespace Tests\Feature;

use App\Core\Services\Pay\ClickFiscalService;
use App\Models\Orders\ClickPayment;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ClickFiscalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'click.merchant_id' => '30347',
            'click.merchant_user_id' => '47620',
            'click.secret_key' => 'test-secret',
            'click.service_id' => '38191',
            'click.api_url' => 'https://api.click.test/v2',
            'click.fiscal' => [
                'enabled' => true,
                'inn' => '311282221',
                'vat_percent' => 0,
                'spic' => '10301003001000000',
                'package_code' => '1500497',
                'item_name' => 'mylib.uz',
            ],
        ]);
    }

    protected function payment(): ClickPayment
    {
        $payment = new ClickPayment();
        $payment->setId(15);
        $payment->setClickPaydocId(987654321);
        $payment->setAmount('25000');

        return $payment;
    }

    public function test_payload_matches_click_ofd_format_with_amounts_in_tiyin(): void
    {
        $payload = (new ClickFiscalService())->buildPayload($this->payment());

        $this->assertSame(38191, $payload['service_id']);
        $this->assertSame(987654321, $payload['payment_id']);
        $this->assertSame(2500000, $payload['received_ecash']);
        $this->assertSame(0, $payload['received_cash']);
        $this->assertSame(0, $payload['received_card']);

        $item = $payload['items'][0];
        $this->assertSame('mylib.uz', $item['Name']);
        $this->assertSame('10301003001000000', $item['SPIC']);
        $this->assertSame('1500497', $item['PackageCode']);
        $this->assertSame(2500000, $item['Price']);
        $this->assertSame(1, $item['Amount']);
        $this->assertSame(2500000, $item['Total']);
        $this->assertSame(0, $item['VATPercent']);
        $this->assertSame('311282221', $item['CommissionInfo']['TIN']);
    }

    public function test_auth_header_is_merchant_user_id_sha1_digest_and_timestamp(): void
    {
        $header = (new ClickFiscalService())->authHeader(1700000000);

        $this->assertSame('47620:' . sha1('1700000000test-secret') . ':1700000000', $header);
    }

    public function test_receipt_is_posted_to_ofd_endpoint_with_auth_header(): void
    {
        Http::fake([
            'https://api.click.test/v2/merchant/payment/ofd_data' => Http::response(['error_code' => 0, 'error_note' => 'Success']),
        ]);

        $sent = (new ClickFiscalService())->sendReceipt($this->payment());

        $this->assertTrue($sent);
        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://api.click.test/v2/merchant/payment/ofd_data'
                && $request->hasHeader('Auth')
                && str_starts_with($request->header('Auth')[0], '47620:')
                && $request['payment_id'] === 987654321
                && $request['items'][0]['SPIC'] === '10301003001000000';
        });
    }

    public function test_receipt_failure_is_reported_but_does_not_throw(): void
    {
        Http::fake([
            '*' => Http::response(['error_code' => -1, 'error_note' => 'Bad request'], 400),
        ]);

        $this->assertFalse((new ClickFiscalService())->sendReceipt($this->payment()));
    }

    public function test_receipt_is_skipped_when_fiscal_is_disabled(): void
    {
        config(['click.fiscal.enabled' => false]);
        Http::fake();

        $this->assertFalse((new ClickFiscalService())->sendReceipt($this->payment()));
        Http::assertNothingSent();
    }

    public function test_receipt_is_skipped_when_paydoc_id_is_missing(): void
    {
        Http::fake();

        $payment = $this->payment();
        $payment->setClickPaydocId(null);

        $this->assertFalse((new ClickFiscalService())->sendReceipt($payment));
        Http::assertNothingSent();
    }

    public function test_amount_conversion_to_tiyin_avoids_float_drift(): void
    {
        $this->assertSame(1999, ClickFiscalService::toTiyin('19.99'));
        $this->assertSame(1000000, ClickFiscalService::toTiyin(10000));
        $this->assertSame(12345, ClickFiscalService::toTiyin(123.45));
    }
}
