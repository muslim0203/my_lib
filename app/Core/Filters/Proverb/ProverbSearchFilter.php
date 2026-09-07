<?php

namespace App\Core\Filters\Proverb;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Products\ProductFilterRequest;
use App\Http\Requests\Proverb\ProverbRequest;
use App\Models\Company\Company;
use App\Models\Products\Product;
use App\Models\Proverbs\Proverb;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProverbSearchFilter
{
    /**
     * @param ProverbRequest $proverbRequest
     * @return LengthAwarePaginator
     */
    public static function search(ProverbRequest $proverbRequest): LengthAwarePaginator
    {
        $query = Proverb::query();

        $proverbRequest->whenFilled('author_uz', function ($value) use ($query) {
            $query->where('author_uz', 'ilike', '%' . $value . '%');
        });

        $proverbRequest->whenFilled('author_oz', function ($value) use ($query) {
            $query->where('author_oz', 'ilike', '%' . $value . '%');
        });

        $proverbRequest->whenFilled('author_ru', function ($value) use ($query) {
            $query->where('author_ru', 'ilike', '%' . $value . '%');
        });

        $proverbRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($proverbRequest));
    }
}
