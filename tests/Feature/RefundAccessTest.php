<?php

namespace Tests\Feature;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Enums\Pay\PaymePaymentStateEnum;
use App\Core\Repository\Product\ProductsOrderRepository;
use App\Core\Services\Pay\ClickService;
use App\Core\Services\Pay\PaymeService;
use App\Core\Services\Product\ProductsOrderService;
use App\Http\Requests\Pay\PaymeRequest;
use App\Http\Requests\Payment\ClickTransferPaymentCompleteFormRequest;
use App\Http\Requests\Payment\ClickTransferPaymentPrepareFormRequest;
use App\Models\Orders\ClickPayment;
use App\Models\Orders\PaymePayment;
use App\Models\Products\Product;
use App\Models\Products\ProductsOrder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

/**
 * To'lov bekor qilinganda yoki pul qaytarilganda kirish huquqi yopilishi
 * (Xaridorlar ofertasi 6.5-band).
 */
class RefundAccessTest extends TestCase
{
    private const SECRET = 'test-secret';
    private const SERVICE_ID = '777';
    private const CUSTOMER = 10;

    protected function setUp(): void
    {
        parent::setUp();

        config(['click.secret_key' => self::SECRET, 'click.service_id' => self::SERVICE_ID, 'click.fiscal.enabled' => false]);

        // Log::info DatabaseHandleLogging orqali shu jadvalga yoziladi.
        Schema::create('app_error_logs', function (Blueprint $table) {
            $table->id();
            foreach (['message', 'context', 'extra', 'level', 'level_name', 'channel', 'ip_address', 'user_agent'] as $column) {
                $table->text($column)->nullable();
            }
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('product_price_types', function (Blueprint $table) {
            $table->id();
            $table->integer('percentage')->default(0);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            foreach (['title_oz', 'title_uz', 'title_ru', 'description_oz', 'description_uz', 'description_ru'] as $column) {
                $table->text($column)->nullable();
            }
            $table->string('price_value')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('author_id')->nullable();
            $table->integer('price_type_id')->nullable();
            $table->timestamps();
        });

        Schema::create('products_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('customer_id');
            $table->integer('author_id')->nullable();
            $table->string('payment_type');
            $table->string('transaction_id')->nullable();
            $table->boolean('enabled')->default(true);
            $table->string('status')->default('new');
            $table->decimal('order_price')->nullable();
            $table->decimal('merchant_money_amount')->nullable();
            $table->string('web_service_price_percentage')->nullable();
            $table->decimal('web_service_money_amount')->nullable();
            $table->integer('payment_system_service_percentage')->nullable();
            $table->decimal('payment_system_service_money_amount')->nullable();
            $table->decimal('web_service_and_payment_system_money_amount')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'customer_id']);
        });

        Schema::create('click_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('user_id');
            $table->decimal('amount');
            $table->string('service_id')->nullable();
            $table->bigInteger('click_trans_id')->nullable();
            $table->bigInteger('click_paydoc_id')->nullable();
            $table->integer('action')->nullable();
            $table->integer('error')->nullable();
            $table->string('error_note')->nullable();
            $table->string('sign_time')->nullable();
            $table->string('sign_string')->nullable();
            $table->integer('merchant_confirm_id')->nullable();
            $table->timestamps();
        });

        Schema::create('payme_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('client_id');
            $table->decimal('amount');
            $table->string('transaction_id')->nullable();
            $table->string('transaction_time')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('state');
            $table->string('transaction_create_time')->nullable();
            $table->string('transaction_perform_time')->nullable();
            $table->string('transaction_cancel_time')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function product(): Product
    {
        $typeId = DB::table('product_price_types')->insertGetId(['percentage' => 25]);

        $product = new Product();
        $product->price_value = '50000';
        $product->author_id = 1;
        $product->price_type_id = $typeId;
        $product->save();

        return $product;
    }

    private function hasAccess(Product $product): bool
    {
        return app(ProductsOrderRepository::class)->hasEntitlement($product->fresh(), self::CUSTOMER);
    }

    private function grant(Product $product, string $type, string $transactionId): void
    {
        app(ProductsOrderService::class)->create(
            $product->getId(), $type, $transactionId, self::CUSTOMER, '50000', '37500', 25
        );
    }

    // ---------------------------------------------------------------
    // Umumiy: revoke va qayta sotib olish
    // ---------------------------------------------------------------

    public function test_revoke_closes_access_and_rebuy_reuses_the_row(): void
    {
        $product = $this->product();
        $this->grant($product, PaymentTypeEnum::TYPE_PAYME->value, 'tx-1');
        $this->assertTrue($this->hasAccess($product));

        $this->assertSame(1, app(ProductsOrderService::class)->revoke($product->getId(), self::CUSTOMER));
        $this->assertFalse($this->hasAccess($product));

        // Unique (product_id, customer_id) buzilmaydi: eski qator qayta yoqiladi.
        $this->grant($product, PaymentTypeEnum::TYPE_CLICK->value, 'tx-2');
        $this->assertTrue($this->hasAccess($product));
        $this->assertSame(1, ProductsOrder::query()->count());
        $this->assertSame('tx-2', ProductsOrder::query()->first()->transaction_id);
    }

    public function test_revoke_with_other_transaction_keeps_access(): void
    {
        $product = $this->product();
        $this->grant($product, PaymentTypeEnum::TYPE_PAYME->value, 'tx-new');

        $this->assertSame(0, app(ProductsOrderService::class)->revoke($product->getId(), self::CUSTOMER, 'tx-old'));
        $this->assertTrue($this->hasAccess($product));
    }

    public function test_artisan_command_revokes_access(): void
    {
        $product = $this->product();
        $this->grant($product, PaymentTypeEnum::TYPE_PAYME->value, 'tx-1');

        $this->artisan('orders:revoke-access', ['product_id' => $product->getId(), 'customer_id' => self::CUSTOMER])
            ->assertSuccessful();
        $this->assertFalse($this->hasAccess($product));

        $this->artisan('orders:revoke-access', ['product_id' => $product->getId(), 'customer_id' => self::CUSTOMER])
            ->assertFailed();
    }

    // ---------------------------------------------------------------
    // Payme
    // ---------------------------------------------------------------

    private function paymeOrder(Product $product, string $state): PaymePayment
    {
        $order = new PaymePayment();
        $order->product_id = $product->getId();
        $order->client_id = self::CUSTOMER;
        $order->amount = '50000';
        $order->transaction_id = 'payme-tx';
        $order->transaction_no = '1';
        $order->state = $state;
        $order->save();

        return $order;
    }

    private function paymeCancel(int $reason): array
    {
        $request = Mockery::mock(PaymeRequest::class);
        $request->shouldReceive('validated')->andReturn(['params' => ['id' => 'payme-tx', 'reason' => $reason]]);

        return app(PaymeService::class)->cancelTransaction($request);
    }

    public function test_payme_cancel_after_perform_refunds_and_revokes_access(): void
    {
        $product = $this->product();
        $this->paymeOrder($product, PaymePaymentStateEnum::PERFORM_TRANSACTION->value);
        $this->grant($product, PaymentTypeEnum::TYPE_PAYME->value, 'payme-tx');

        // reason=3 ilgari xato ravishda -1 berardi.
        $result = $this->paymeCancel(3);

        $this->assertSame(-2, $result['state']);
        $this->assertFalse($this->hasAccess($product));
    }

    public function test_payme_cancel_before_perform_does_not_touch_other_access(): void
    {
        $product = $this->product();
        $this->paymeOrder($product, PaymePaymentStateEnum::CREATE_TRANSACTION->value);
        $this->grant($product, PaymentTypeEnum::TYPE_CLICK->value, 'click-other');

        $result = $this->paymeCancel(5);

        $this->assertSame(-1, $result['state']);
        $this->assertTrue($this->hasAccess($product));
    }

    // ---------------------------------------------------------------
    // Click
    // ---------------------------------------------------------------

    private function clickParams(ClickPayment $payment, int $action, int $error, bool $withPrepareId): array
    {
        $params = [
            'click_trans_id' => '555',
            'service_id' => self::SERVICE_ID,
            'click_paydoc_id' => '666',
            'merchant_trans_id' => (string) $payment->id,
            'amount' => '50000',
            'action' => (string) $action,
            'error' => (string) $error,
            'error_note' => $error === 0 ? 'Success' : 'Failed',
            'sign_time' => '2026-10-16 12:00:00',
        ];

        if ($withPrepareId) {
            $params['merchant_prepare_id'] = (string) $payment->id;
        }

        $params['sign_string'] = md5(join('', [
            $params['click_trans_id'], $params['service_id'], self::SECRET, $params['merchant_trans_id'],
            $params['merchant_prepare_id'] ?? '', $params['amount'], $params['action'], $params['sign_time'],
        ]));

        return $params;
    }

    private function clickPayment(Product $product): ClickPayment
    {
        $payment = new ClickPayment();
        $payment->product_id = $product->getId();
        $payment->user_id = self::CUSTOMER;
        $payment->amount = '50000';
        $payment->save();

        return $payment;
    }

    private function clickPrepare(ClickPayment $payment): void
    {
        $request = Mockery::mock(ClickTransferPaymentPrepareFormRequest::class);
        $request->shouldReceive('validated')->andReturn($this->clickParams($payment, 0, 0, false));

        app(ClickService::class)->preparePayment($request);
    }

    private function clickComplete(ClickPayment $payment, int $error): void
    {
        $request = Mockery::mock(ClickTransferPaymentCompleteFormRequest::class);
        $request->shouldReceive('validated')->andReturn($this->clickParams($payment, 1, $error, true));

        app(ClickService::class)->completePayment($request);
    }

    public function test_click_prepare_alone_does_not_grant_access(): void
    {
        $product = $this->product();
        $this->clickPrepare($this->clickPayment($product));

        $this->assertFalse($this->hasAccess($product));
    }

    public function test_click_successful_complete_grants_access(): void
    {
        $product = $this->product();
        $payment = $this->clickPayment($product);
        $this->clickPrepare($payment);
        $this->clickComplete($payment->fresh(), 0);

        $this->assertTrue($this->hasAccess($product));
    }

    public function test_click_failed_complete_does_not_grant_and_revokes_legacy_access(): void
    {
        $product = $this->product();
        $payment = $this->clickPayment($product);
        $this->clickPrepare($payment);

        // Eski kod kirishni prepare da berardi: shunday order bor deb faraz qilamiz.
        $this->grant($product, PaymentTypeEnum::TYPE_CLICK->value, '555');

        $this->clickComplete($payment->fresh(), -9);

        $this->assertFalse($this->hasAccess($product));
    }
}
