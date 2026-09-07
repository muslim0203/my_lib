<?php

namespace App\Core\Repository\Product;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    /**
     * @param int $author_id
     * @return array
     */
    public function findAllByUserId(int $author_id): array
    {
        return Product::query()
            ->where('author_id', $author_id)
            ->get()
            ->all();
    }

    /**
     * @param array $ids
     * @param int $limit
     * @return array
     */
    public function findAllByIds(array $ids, int $limit = 10): array
    {
        return Product::query()
            ->whereIn('id', $ids)
            ->limit($limit)
            ->get()
            ->all();
    }

    /**
     * @param int $id
     * @return Builder|Product
     */
    public function getById(int $id): Product|Builder
    {
        return Product::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param int $id
     * @return Builder|Product|null
     */
    public function findById(int $id): Product|Builder|null
    {
        return Product::query()
            ->where('id', $id)
            ->first();
    }

    /**
     * @param Product $product
     * @param array $options
     * @return void
     */
    public function save(Product $product, array $options = []): void
    {
        if (!$product->save($options)) {
            throw new \RuntimeException(__('Saving error.'));
        }
    }

    /**
     * @param int $id
     * @param int $userId
     * @return Product|Builder
     */
    public function getByIdAndUser(int $id, int $userId): Product|Builder
    {
        return Product::query()
            ->where('id', $id)
            ->where('author_id', $userId)
            ->firstOrFail();
    }


    /**
     * @param int $id
     * @param int $userId
     * @return Builder|Product|null
     */
    public function findByIdAndUser(int $id, int $userId): Product|Builder|null
    {
        return Product::query()
            ->where('id', $id)
            ->where('author_id', $userId)
            ->first();
    }


    /**
     * @param int $authorId
     * @param int $typeId
     * @return Collection|array
     */
    public function getAuthorProductList(int $authorId, int $typeId): Collection|array
    {
        return Product::query()
            ->from('products as p')
            ->select([
                'p.id',
                'p.' . LanguageHelper::getTitle() . ' as title',
                'ept.' . LanguageHelper::getName() . ' as type_name',
            ])
            ->addSelect(DB::raw("
                CONCAT(f.path,'/',f.file_name) AS wrapper_file
            "))
            ->leftJoin('files as f', 'f.id', '=', 'p.wrapper_file_id')
            ->join('link_product_types as lp', 'lp.product_id', '=', 'p.id')
            ->leftJoin('enum_product_types as ept', 'ept.id', '=', 'lp.type_id')
            ->where('p.author_id', $authorId)
            ->where('lp.type_id', $typeId)
            ->orderBy('p.created_at', 'desc')
            ->get();
    }

    public function getView(int $id)
    {
        $name = LanguageHelper::getName();
        return Product::query()
            ->from('products as p')
            ->select(DB::raw("
                p.*,
                CASE WHEN u.social_user_id IS NOT NULL THEN CONCAT(su.first_name, ' ', su.last_name,' ',su.middle_name)
                WHEN u.employee_id IS NOT NULL THEN CONCAT(e.first_name, ' ', e.last_name,' ',e.middle_name) END AS author_name,
                CONCAT(wf.path,'/',wf.file_name) AS wrapper_file,
                wf.original_name AS wrapper_file_name,
                CONCAT(sf.path,'/',sf.file_name) AS source_file,
                sf.original_name AS source_file_name,
                ept.{$name} AS type_name,
                eps.{$name} AS status_name,
                pp.price,
                pd.discount,
                pd.from_expire_at,
                pd.to_expire_at
            "))
            ->leftJoin('users as u', 'u.id', '=', 'p.author_id')
            ->leftJoin('social_users as su', 'su.id', '=', 'u.social_user_id')
            ->leftJoin('employees as e', 'e.id', '=', 'u.employee_id')
            ->leftJoin('files as wf', 'wf.id', '=', 'p.wrapper_file_id')
            ->leftJoin('files as sf', 'sf.id', '=', 'p.source_file_id')
            ->leftJoin('link_product_types as lp', 'lp.product_id', '=', 'p.id')
            ->leftJoin('enum_product_types as ept', 'ept.id', '=', 'lp.type_id')
            ->leftJoin('enum_product_status as eps', 'ept.id', '=', 'p.status_id')
            ->leftJoin('product_prices as pp', 'pp.id', '=', 'p.price_id')
            ->leftJoin('product_discounts as pd', 'pd.id', '=', 'p.discount_id')
            ->where('p.id', $id)
            ->first();
    }
}
