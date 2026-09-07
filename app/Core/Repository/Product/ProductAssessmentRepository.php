<?php

namespace App\Core\Repository\Product;

use App\Models\Products\ProductAssessment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductAssessmentRepository
{
    /**
     * @param ProductAssessment $productAssessment
     * @param array $options
     * @return void
     */
    public function save(ProductAssessment $productAssessment, array $options = []): void
    {
        if (!$productAssessment->save($options)) {
            throw new \RuntimeException(__('client.Save error assessment of product'));
        }
    }

    /**
     * @param int $user_id
     * @param int $product_id
     * @return Builder|ProductAssessment|null
     */
    public function findByUserAndProduct(int $user_id, int $product_id): ProductAssessment|Builder|null
    {
        return ProductAssessment::query()
            ->where('author_id', $user_id)
            ->where('product_id', $product_id)
            ->first();
    }

    /**
     * @param int $product_id
     * @return array
     */
    public function findAllByProduct(int $product_id): array
    {
        return ProductAssessment::query()
            ->selectRaw('level, author_id, count(*) as amount')
            ->where('product_id', $product_id)
            ->groupBy(DB::raw('level, author_id'))
            ->orderByDesc('level')
            ->get()
            ->all();
    }

    /**
     * @param int $product_id
     * @return Builder|ProductAssessment|null
     */
    public function averageAmountByProduct(int $product_id): ProductAssessment|Builder|null
    {
        return ProductAssessment::query()
            ->selectRaw('product_id, COUNT(*) as amount, SUM(level) as level')
            ->where('product_id', $product_id)
            ->groupBy('product_id')
            ->first();
    }
}
