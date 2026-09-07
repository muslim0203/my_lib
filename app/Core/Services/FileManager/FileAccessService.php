<?php

namespace App\Core\Services\FileManager;

use App\Core\Repository\Product\ProductsOrderRepository;
use App\Models\Files\File;
use App\Models\Products\Product;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Fayllarga kirishning yagona chegarasi.
 *
 * Fayl nomi yoki User-Agent sarlavhasi avtorizatsiya emas. Bu yerda har
 * bir fayl uchun uning qanday bog'langaniga qarab himoya darajasi
 * aniqlanadi va faqat huquqi bor foydalanuvchiga o'qish beriladi.
 */
class FileAccessService
{
    /**
     * Shaxsiy hujjatlar joylashgan ustunlar. Bu fayllar hech qachon
     * ochiq bo'lmaydi.
     */
    protected const DOCUMENT_REFERENCES = [
        ['table' => 'authors', 'column' => 'diploma_file_id'],
        ['table' => 'authors', 'column' => 'licence_file_id'],
        ['table' => 'authority', 'column' => 'certificate_file_id'],
        ['table' => 'authority', 'column' => 'patent_file_id'],
        ['table' => 'products', 'column' => 'licence_file_id'],
    ];

    public const ACCESS_PUBLIC = 'public';
    public const ACCESS_PAID = 'paid';
    public const ACCESS_DOCUMENT = 'document';

    /**
     * @var array<string, bool>
     */
    protected static array $tableCache = [];

    /**
     * Fayl yozuvini nomi bo'yicha topadi.
     *
     * Yo'l birlashtirilmaydi, shuning uchun `../` kabi traversal
     * urinishlari mumkin emas: mavjud bo'lmagan nom 404 beradi.
     *
     * @param string $fileName
     * @return File|null
     */
    public function findByName(string $fileName): ?File
    {
        return File::query()
            ->where('file_name', $fileName)
            ->first();
    }

    /**
     * Faylning himoya darajasi.
     *
     * @param File $file
     * @return string
     */
    public function classify(File $file): string
    {
        if ($this->isDocument($file)) {
            return self::ACCESS_DOCUMENT;
        }

        foreach ($this->relatedProducts($file) as $product) {
            if (!$product->isFree()) {
                return self::ACCESS_PAID;
            }
        }

        return self::ACCESS_PUBLIC;
    }

    /**
     * Joriy foydalanuvchi shu faylni o'qiy oladimi.
     *
     * @param File $file
     * @return bool
     */
    public function allows(File $file): bool
    {
        $access = $this->classify($file);

        if ($access === self::ACCESS_PUBLIC) {
            return true;
        }

        if (!Auth::check()) {
            return false;
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        // Shaxsiy hujjatlarni faqat moderatsiya qiluvchi xodim ko'radi.
        // Bu ataylab qattiq qoida: noaniq holatda kirish yopiladi.
        if ($access === self::ACCESS_DOCUMENT) {
            return !empty($user->employee_id);
        }

        if (!empty($user->employee_id)) {
            return true;
        }

        /**
         * @var ProductsOrderRepository $productsOrderRepository
         */
        $productsOrderRepository = app(ProductsOrderRepository::class);

        foreach ($this->relatedProducts($file) as $product) {
            if ($productsOrderRepository->hasEntitlement($product, $user->getId())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Faylni saqlagan diskni topadi.
     *
     * Avval maxfiy disk, so'ng eski joylashuvlar tekshiriladi, shuning
     * uchun hali ko'chirilmagan fayllar ham o'qiladi.
     *
     * @param File $file
     * @return string|null
     */
    public function resolveDisk(File $file): ?string
    {
        $disks = array_merge(
            [config('filesystems.upload_disk')],
            (array)config('filesystems.legacy_read_disks', [])
        );

        foreach (array_unique(array_filter($disks)) as $disk) {
            if (Storage::disk($disk)->exists($file->getFileName())) {
                return $disk;
            }
        }

        return null;
    }

    /**
     * Fayl mahsulotning pullik kontenti sifatida bog'langanmi.
     *
     * @param File $file
     * @return \Illuminate\Support\Collection<int, Product>
     */
    protected function relatedProducts(File $file): \Illuminate\Support\Collection
    {
        $fileId = $file->getId();

        return Product::query()
            ->where(function ($query) use ($fileId) {
                $query->where('source_file_id', $fileId);

                if ($this->hasTable('link_product_files')) {
                    $query->orWhereExists(function ($sub) use ($fileId) {
                        $sub->select(DB::raw(1))
                            ->from('link_product_files')
                            ->whereColumn('link_product_files.product_id', 'products.id')
                            ->where('link_product_files.file_id', $fileId);
                    });
                }
            })
            ->get();
    }

    /**
     * @param File $file
     * @return bool
     */
    protected function isDocument(File $file): bool
    {
        foreach (self::DOCUMENT_REFERENCES as $reference) {
            if (!$this->hasTable($reference['table'])) {
                continue;
            }

            $exists = DB::table($reference['table'])
                ->where($reference['column'], $file->getId())
                ->exists();

            if ($exists) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $table
     * @return bool
     */
    protected function hasTable(string $table): bool
    {
        if (!array_key_exists($table, self::$tableCache)) {
            self::$tableCache[$table] = Schema::hasTable($table);
        }

        return self::$tableCache[$table];
    }
}
