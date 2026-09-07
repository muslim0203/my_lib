<?php

namespace Tests\Feature;

use App\Core\Services\Pay\ClickService;
use App\Http\Middleware\PaymeAuthenticationMiddleware;
use Illuminate\Http\Request;
use ReflectionMethod;
use Tests\TestCase;

/**
 * To'lov provayderlarining autentifikatsiyasi uchun regressiya testlari.
 *
 * Asosiy talab: sozlanmagan integratsiya hech kimni tasdiqlamaydi.
 * Bo'sh sir bilan imzo hisoblansa yoki bo'sh parol bilan solishtirilsa,
 * hujumchi haqiqiy callback'ni o'zi yasab, pullik mahsulotni ochib
 * olishi mumkin bo'lardi.
 */
class PaymentSecurityTest extends TestCase
{
    /**
     * @param array $params
     * @return bool
     */
    protected function clickSignatureMatches(array $params): bool
    {
        $method = new ReflectionMethod(ClickService::class, 'isMatchSignKey');
        $method->setAccessible(true);

        return $method->invoke(new ClickService(), $params);
    }

    /**
     * @param string $secret
     * @return array
     */
    protected function signedClickParams(string $secret): array
    {
        $params = [
            'click_trans_id' => '111',
            'service_id' => '222',
            'merchant_trans_id' => '333',
            'merchant_prepare_id' => '444',
            'amount' => '50000',
            'action' => '1',
            'sign_time' => '2026-09-07 12:00:00',
        ];

        $params['sign_string'] = md5(join('', [
            $params['click_trans_id'],
            $params['service_id'],
            $secret,
            $params['merchant_trans_id'],
            $params['merchant_prepare_id'],
            $params['amount'],
            $params['action'],
            $params['sign_time'],
        ]));

        return $params;
    }

    public function test_click_signature_is_accepted_when_secret_matches(): void
    {
        config(['click.secret_key' => 'real-secret']);

        $this->assertTrue(
            $this->clickSignatureMatches($this->signedClickParams('real-secret'))
        );
    }

    public function test_click_signature_is_rejected_when_secret_differs(): void
    {
        config(['click.secret_key' => 'real-secret']);

        $this->assertFalse(
            $this->clickSignatureMatches($this->signedClickParams('guessed-secret'))
        );
    }

    /**
     * Eng muhim holat: sir sozlanmagan. Ilgari imzo bo'sh sir bilan
     * hisoblanardi, ya'ni algoritmni bilgan har kim to'g'ri imzo yasay
     * olardi.
     */
    public function test_click_callback_is_rejected_when_secret_is_not_configured(): void
    {
        config(['click.secret_key' => null]);

        $this->assertFalse(
            $this->clickSignatureMatches($this->signedClickParams('')),
            'Sozlanmagan sir bilan imzo qabul qilinmasligi kerak'
        );
    }

    public function test_click_signature_is_rejected_when_sign_string_is_missing(): void
    {
        config(['click.secret_key' => 'real-secret']);

        $params = $this->signedClickParams('real-secret');
        unset($params['sign_string']);

        $this->assertFalse($this->clickSignatureMatches($params));
    }

    /**
     * @param string|null $authorization
     * @return int
     */
    protected function paymeStatus(?string $authorization): int
    {
        $request = Request::create('/api/payme/pay', 'POST', ['id' => 1]);

        if ($authorization !== null) {
            $request->headers->set('Authorization', $authorization);
        }

        $middleware = new PaymeAuthenticationMiddleware();

        $response = $middleware->handle($request, function () {
            return response('passed', 200);
        });

        return $response->getStatusCode();
    }

    /**
     * @return string
     */
    protected function paymeResponseBody(?string $authorization): string
    {
        $request = Request::create('/api/payme/pay', 'POST', ['id' => 1]);

        if ($authorization !== null) {
            $request->headers->set('Authorization', $authorization);
        }

        $middleware = new PaymeAuthenticationMiddleware();

        return $middleware->handle($request, function () {
            return response('passed', 200);
        })->getContent();
    }

    public function test_payme_accepts_correct_basic_credentials(): void
    {
        config(['payme.login' => 'paycom', 'payme.key' => 'super-key']);

        $header = 'Basic ' . base64_encode('paycom:super-key');

        $this->assertSame('passed', $this->paymeResponseBody($header));
    }

    public function test_payme_rejects_wrong_password(): void
    {
        config(['payme.login' => 'paycom', 'payme.key' => 'super-key']);

        $header = 'Basic ' . base64_encode('paycom:wrong');

        $this->assertStringContainsString('Authentication failed', $this->paymeResponseBody($header));
    }

    /**
     * Ilgari `explode(':')` natijasi ikkiga ajratilardi va ikki nuqtasiz
     * sarlavha PHP 8 da xato berib, 500 qaytarardi.
     */
    public function test_payme_rejects_malformed_header_without_error(): void
    {
        config(['payme.login' => 'paycom', 'payme.key' => 'super-key']);

        foreach ([
            'Basic ' . base64_encode('no-colon-here'),
            'Basic !!!not-base64!!!',
            'Bearer ' . base64_encode('paycom:super-key'),
            'Basic',
            '',
        ] as $header) {
            $this->assertSame(200, $this->paymeStatus($header));
            $this->assertStringContainsString(
                'Authentication failed',
                $this->paymeResponseBody($header),
                "Buzuq sarlavha rad etilishi kerak: {$header}"
            );
        }
    }

    /**
     * Parol ichida ikki nuqta bo'lishi mumkin - faqat birinchisi
     * bo'yicha ajratiladi.
     */
    public function test_payme_password_may_contain_a_colon(): void
    {
        config(['payme.login' => 'paycom', 'payme.key' => 'a:b:c']);

        $header = 'Basic ' . base64_encode('paycom:a:b:c');

        $this->assertSame('passed', $this->paymeResponseBody($header));
    }

    public function test_payme_rejects_everything_when_not_configured(): void
    {
        config(['payme.login' => null, 'payme.key' => null]);

        $header = 'Basic ' . base64_encode(':');

        $this->assertStringContainsString('Authentication failed', $this->paymeResponseBody($header));
        $this->assertStringContainsString('Authentication failed', $this->paymeResponseBody(null));
    }
}
