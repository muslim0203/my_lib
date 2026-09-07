<?php

namespace App\Core\Filters\Product;

use App\Models\Products\ProductsOrder;
use App\Models\Users\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductsOrderFilterByAuthor
{
    /**
     * @param FormRequest $formRequest
     * @return LengthAwarePaginator
     */
    public static function search(FormRequest $formRequest): LengthAwarePaginator
    {
        if (!Auth::check()) {
            abort(403, __('client.Forbidden'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $query = ProductsOrder::query()
            ->with([
                'product' => ['wrapperFile']
            ])
            ->where('author_id', $user->getId())
            ->orderBy('created_at', 'desc');

        return $query->paginate(30);
    }
}
