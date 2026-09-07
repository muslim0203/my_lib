<?php

namespace Tests\Feature;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Repository\Product\ProductsOrderRepository;
use App\Core\Services\FileManager\FileAccessService;
use App\Models\Files\File;
use App\Models\Products\Product;
use App\Models\Products\ProductsOrder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Pullik kontentga kirish chegarasi uchun regressiya testlari.
 *
 * Loyihaning to'liq migratsiya to'plami SQLite'da ishlamaydi (indeksga
 * bog'liq `drop column`), shuning uchun bu yerda faqat kerakli jadvallar
 * tuziladi. Tekshirilayotgan mantiq - haqiqiy kod, mock emas.
 */
class EntitlementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Product::boot() yaratishda sarlavha/tavsif maydonlarini
            // to'ldiradi, shuning uchun ular ham kerak.
            foreach (['title_oz', 'title_uz', 'title_ru',
                      'description_oz', 'description_uz', 'description_ru'] as $column) {
                $table->text($column)->nullable();
            }
            $table->string('price_value')->nullable();
            $table->integer('author_id')->nullable();
            $table->integer('source_file_id')->nullable();
            $table->integer('licence_file_id')->nullable();
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
            $table->timestamps();
            $table->unique(['product_id', 'customer_id']);
        });

        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('original_name')->nullable();
            $table->string('hash')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('extension')->nullable();
            $table->string('size')->nullable();
            $table->string('path')->nullable();
            $table->timestamps();
        });

        Schema::create('link_product_files', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('file_id');
            $table->boolean('enabled')->default(true);
        });
    }

    protected function makeProduct(?string $price): Product
    {
        $product = new Product();
        $product->price_value = $price;
        $product->author_id = 1;
        $product->save();

        return $product;
    }

    protected function makeOrder(
        int $productId,
        int $customerId,
        string $paymentType,
        bool $enabled = true
    ): void {
        $order = new ProductsOrder();
        $order->product_id = $productId;
        $order->customer_id = $customerId;
        $order->author_id = 1;
        $order->payment_type = $paymentType;
        $order->transaction_id = 'tx-' . $productId . '-' . $customerId;
        $order->enabled = $enabled;
        $order->save();
    }

    public function test_price_value_decides_whether_a_product_is_free(): void
    {
        $this->assertTrue($this->makeProduct(null)->isFree());
        $this->assertTrue($this->makeProduct('')->isFree());
        $this->assertTrue($this->makeProduct('0')->isFree());
        $this->assertTrue($this->makeProduct('0.00')->isFree());

        $this->assertFalse($this->makeProduct('50000')->isFree());
        $this->assertFalse($this->makeProduct('0.01')->isFree());
    }

    /**
     * Auditdagi S1 zaifligi: `free` turidagi order pullik mahsulotga
     * kirish bermasligi kerak. Bunday qatorlar bazada allaqachon
     * bo'lishi mumkin, chunki eski GET marshruti ularni yaratib bergan.
     */
    public function test_free_order_does_not_unlock_a_paid_product(): void
    {
        $repository = app(ProductsOrderRepository::class);
        $product = $this->makeProduct('50000');

        $this->makeOrder($product->getId(), 7, PaymentTypeEnum::TYPE_FREE->value);

        $this->assertTrue(
            $repository->existsProductOrder($product->getId(), 7),
            'Order qatori mavjud bo\'lishi kerak'
        );

        $this->assertFalse(
            $repository->hasEntitlement($product, 7),
            'Pullik mahsulot bepul order orqali ochilmasligi kerak'
        );
    }

    public function test_paid_order_unlocks_a_paid_product(): void
    {
        $repository = app(ProductsOrderRepository::class);
        $product = $this->makeProduct('50000');

        $this->makeOrder($product->getId(), 7, PaymentTypeEnum::TYPE_PAYME->value);

        $this->assertTrue($repository->hasEntitlement($product, 7));
    }

    public function test_disabled_order_does_not_grant_access(): void
    {
        $repository = app(ProductsOrderRepository::class);
        $product = $this->makeProduct('50000');

        $this->makeOrder($product->getId(), 7, PaymentTypeEnum::TYPE_CLICK->value, false);

        $this->assertFalse($repository->hasEntitlement($product, 7));
    }

    public function test_other_user_has_no_access(): void
    {
        $repository = app(ProductsOrderRepository::class);
        $product = $this->makeProduct('50000');

        $this->makeOrder($product->getId(), 7, PaymentTypeEnum::TYPE_PAYME->value);

        $this->assertFalse($repository->hasEntitlement($product, 8));
    }

    public function test_free_product_is_unlocked_by_a_free_order(): void
    {
        $repository = app(ProductsOrderRepository::class);
        $product = $this->makeProduct(null);

        $this->makeOrder($product->getId(), 7, PaymentTypeEnum::TYPE_FREE->value);

        $this->assertTrue($repository->hasEntitlement($product, 7));
    }

    /**
     * Bir vaqtda kelgan ikkita bepul xarid so'rovi ikkita order
     * yarata olmaydi: baza darajasidagi unique cheklov to'xtatadi.
     */
    public function test_duplicate_order_is_rejected_by_the_database(): void
    {
        $product = $this->makeProduct(null);

        $this->makeOrder($product->getId(), 7, PaymentTypeEnum::TYPE_FREE->value);

        $this->expectException(UniqueConstraintViolationException::class);

        $this->makeOrder($product->getId(), 7, PaymentTypeEnum::TYPE_FREE->value);
    }

    public function test_source_file_of_a_paid_product_is_classified_as_paid(): void
    {
        $service = app(FileAccessService::class);

        $file = new File();
        $file->file_name = 'paid-book.pdf';
        $file->original_name = 'book.pdf';
        $file->save();

        $product = $this->makeProduct('50000');
        $product->source_file_id = $file->getId();
        $product->save();

        $this->assertSame(FileAccessService::ACCESS_PAID, $service->classify($file));
        $this->assertFalse(
            $service->allows($file),
            'Anonim foydalanuvchi pullik faylni ocha olmasligi kerak'
        );
    }

    public function test_audio_file_linked_to_a_paid_product_is_protected(): void
    {
        $service = app(FileAccessService::class);

        $file = new File();
        $file->file_name = 'chapter-1.mp3';
        $file->original_name = 'chapter.mp3';
        $file->save();

        $product = $this->makeProduct('50000');

        DB::table('link_product_files')->insert([
            'product_id' => $product->getId(),
            'file_id' => $file->getId(),
            'enabled' => true,
        ]);

        $this->assertSame(FileAccessService::ACCESS_PAID, $service->classify($file));
        $this->assertFalse($service->allows($file));
    }

    public function test_cover_of_a_free_product_stays_public(): void
    {
        $service = app(FileAccessService::class);

        $file = new File();
        $file->file_name = 'cover.png';
        $file->original_name = 'cover.png';
        $file->save();

        $product = $this->makeProduct(null);
        $product->source_file_id = $file->getId();
        $product->save();

        $this->assertSame(FileAccessService::ACCESS_PUBLIC, $service->classify($file));
        $this->assertTrue($service->allows($file));
    }

    /**
     * Fayl yozuv bo'yicha topiladi, yo'l birlashtirilmaydi - shuning
     * uchun `../` kabi nomlar hech qanday faylga olib bormaydi.
     */
    public function test_path_traversal_name_resolves_to_nothing(): void
    {
        $service = app(FileAccessService::class);

        $this->assertNull($service->findByName('../../.env'));
        $this->assertNull($service->findByName('..%2F..%2F.env'));
    }
}
