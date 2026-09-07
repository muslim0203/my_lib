<?php

namespace App\Core\Filters\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaginationFilter
{
    /**
     * Default number of rows per page.
     */
    public const DEFAULT_PER_PAGE = 20;

    /**
     * Hard upper bound, so `?perPage=999999999` cannot be used to force the
     * database to materialise the whole report.
     */
    public const MAX_PER_PAGE = 200;

    /**
     * @param string $dbQuery
     * @param string $totalQuery
     * @param FormRequest $formRequest
     * @param array<string, mixed> $bindings Named bindings shared by both queries.
     * @return LengthAwarePaginator
     */
    public static function wrap(
        string      $dbQuery,
        string      $totalQuery,
        FormRequest $formRequest,
        array       $bindings = []
    ): LengthAwarePaginator
    {
        $page = self::toBoundedInt($formRequest->input('page'), 1, 1, PHP_INT_MAX);
        $perPage = self::toBoundedInt(
            $formRequest->input('perPage'),
            self::DEFAULT_PER_PAGE,
            1,
            self::MAX_PER_PAGE
        );

        $offset = ($page - 1) * $perPage;

        $dbQuery .= " LIMIT :limit OFFSET :offset";

        $rows = DB::select($dbQuery, array_merge($bindings, [
            'limit'  => $perPage,
            'offset' => $offset,
        ]));

        // The total query previously received NO bindings at all, which broke
        // as soon as the query used placeholders.
        $totalRows = DB::select($totalQuery, $bindings);
        $total = empty($totalRows) ? 0 : (int) ($totalRows[0]->total ?? 0);

        return new LengthAwarePaginator($rows, $total, $perPage, $page, [
            'path' => $formRequest->url(),
            'query' => $formRequest->query(),
        ]);
    }

    /**
     * `page` and `perPage` arrive straight from user input: a non numeric value
     * used to raise a TypeError and a huge value produced an unbounded query.
     *
     * @param mixed $value
     * @param int $default
     * @param int $min
     * @param int $max
     * @return int
     */
    private static function toBoundedInt(mixed $value, int $default, int $min, int $max): int
    {
        if (!is_scalar($value) || is_bool($value) || !is_numeric($value)) {
            return $default;
        }

        return max($min, min($max, (int) $value));
    }
}
