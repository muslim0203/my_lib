<?php

namespace App\Core\Filters\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaginationFilter
{
    /**
     * @param string $dbQuery
     * @param string $totalQuery
     * @param FormRequest $formRequest
     * @return LengthAwarePaginator
     */
    public static function wrap(
        string      $dbQuery,
        string      $totalQuery,
        FormRequest $formRequest
    ): LengthAwarePaginator
    {
        $page = $formRequest->input('page', 1);
        $perPage = $formRequest->input('perPage', 20);

        $offset = ($page - 1) * $perPage;

        $dbQuery .= " LIMIT :limit OFFSET :offset";

        $dbQuery = DB::select($dbQuery, ['limit' => $perPage, 'offset' => $offset]);
        $total = DB::select($totalQuery)[0]->total;

        return new LengthAwarePaginator($dbQuery, $total, $perPage, $page, [
            'path' => $formRequest->url(),
            'query' => $formRequest->query(),
        ]);
    }
}
