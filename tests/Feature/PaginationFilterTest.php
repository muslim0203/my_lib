<?php

namespace Tests\Feature;

use App\Core\Filters\Reports\PaginationFilter;
use App\Http\Requests\Reports\PurchaseFormRequest;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * `PaginationFilter::wrap()` uchun regressiya testlari.
 *
 * Ilgari bu yerda uchta muammo bor edi:
 *   1. umumiy sonni hisoblovchi so'rov HECH QANDAY binding olmasdi;
 *   2. `page`/`perPage` to'g'ridan-to'g'ri kirishdan olinardi - raqam
 *      bo'lmagan qiymat TypeError, katta qiymat esa cheklanmagan so'rov
 *      berardi;
 *   3. `DB::select(...)[0]->total` bo'sh natijada xato berardi.
 */
class PaginationFilterTest extends TestCase
{
    /**
     * @var array<int, array{sql: string, bindings: array<string, mixed>}>
     */
    protected array $queries = [];

    /**
     * Umumiy son so'roviga qaytariladigan qiymat.
     */
    protected ?int $totalToReturn = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queries = [];

        DB::shouldReceive('select')->andReturnUsing(
            function (string $sql, array $bindings = []): array {
                $this->queries[] = ['sql' => $sql, 'bindings' => $bindings];

                $isTotalQuery = !str_contains($sql, 'LIMIT :limit');

                if ($isTotalQuery && $this->totalToReturn !== null) {
                    return [(object) ['total' => $this->totalToReturn]];
                }

                return [];
            }
        );
    }

    /**
     * @param array<string, mixed> $input
     * @param array<string, mixed> $bindings
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function wrap(array $input = [], array $bindings = [])
    {
        $request = PurchaseFormRequest::create(
            'http://localhost/api/report/purchase',
            'POST',
            $input
        );

        return PaginationFilter::wrap(
            'select * from list',
            'select count(*) as total from list',
            $request,
            $bindings
        );
    }

    protected function rowsQuery(): array
    {
        return $this->queries[0];
    }

    protected function totalQuery(): array
    {
        return $this->queries[1];
    }

    public function test_limit_and_offset_are_bound_not_interpolated(): void
    {
        $this->wrap(['page' => '3', 'perPage' => '10']);

        $query = $this->rowsQuery();

        $this->assertStringContainsString('LIMIT :limit OFFSET :offset', $query['sql']);
        $this->assertSame(10, $query['bindings']['limit']);
        $this->assertSame(20, $query['bindings']['offset']);
    }

    public function test_per_page_is_capped(): void
    {
        $this->wrap(['perPage' => '999999999']);

        $this->assertSame(
            PaginationFilter::MAX_PER_PAGE,
            $this->rowsQuery()['bindings']['limit']
        );
    }

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function invalidPerPageProvider(): array
    {
        return [
            'matn' => ['abc'],
            'bo\'sh' => [''],
            'massiv' => [['a']],
            'mantiqiy' => [true],
        ];
    }

    /**
     * @dataProvider invalidPerPageProvider
     */
    public function test_invalid_per_page_falls_back_to_the_default(mixed $perPage): void
    {
        $this->wrap(['perPage' => $perPage]);

        $this->assertSame(
            PaginationFilter::DEFAULT_PER_PAGE,
            $this->rowsQuery()['bindings']['limit']
        );
    }

    /**
     * Raqam bo'lmagan `page` ilgari arifmetikada TypeError berardi.
     */
    public function test_invalid_page_falls_back_to_the_first_page(): void
    {
        $this->wrap(['page' => 'not-a-number']);

        $this->assertSame(0, $this->rowsQuery()['bindings']['offset']);
    }

    public function test_page_below_one_is_clamped(): void
    {
        $this->wrap(['page' => '-5']);

        $this->assertSame(0, $this->rowsQuery()['bindings']['offset']);
    }

    /**
     * Asosiy M4 tuzatishi: ilgari `DB::select($totalQuery)` bindingsiz
     * chaqirilardi va placeholder ishlatilgan zahoti buzilardi.
     */
    public function test_caller_bindings_reach_both_queries(): void
    {
        $this->wrap([], ['author_id' => 7, 'from_date' => '2026-01-01']);

        foreach ([$this->rowsQuery(), $this->totalQuery()] as $query) {
            $this->assertSame(7, $query['bindings']['author_id']);
            $this->assertSame('2026-01-01', $query['bindings']['from_date']);
        }
    }

    /**
     * `limit`/`offset` faqat qatorlar so'roviga tegishli.
     */
    public function test_total_query_does_not_receive_limit_and_offset(): void
    {
        $this->wrap([], ['author_id' => 7]);

        $total = $this->totalQuery()['bindings'];

        $this->assertArrayNotHasKey('limit', $total);
        $this->assertArrayNotHasKey('offset', $total);
    }

    public function test_empty_total_result_does_not_error(): void
    {
        $paginator = $this->wrap();

        $this->assertSame(0, $paginator->total());
    }

    public function test_total_is_taken_from_the_count_query(): void
    {
        $this->totalToReturn = 137;

        $paginator = $this->wrap(['perPage' => '10']);

        $this->assertSame(137, $paginator->total());
        $this->assertSame(10, $paginator->perPage());
    }
}
