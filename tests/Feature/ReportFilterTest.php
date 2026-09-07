<?php

namespace Tests\Feature;

use App\Core\Enums\Reports\ReportTypeEnum;
use App\Core\Filters\Reports\ReportFilterByBenefit;
use App\Core\Filters\Reports\ReportFilterByBooks;
use App\Core\Filters\Reports\ReportFilterByCustomer;
use App\Http\Requests\Reports\PurchaseFormRequest;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Hisobot filtrlari uchun regressiya testlari.
 *
 * Bu so'rovlar PostgreSQL'ga xos (`json_agg`, `filter (where ...)`,
 * `::numeric`, `||`), mashinada esa PostgreSQL yo'q. Shu sababli SQL
 * BAJARILMAYDI: `DB::select` ushlab qolinadi va tekshiriladigan narsa -
 * qanday so'rov qurilgani va qiymatlar qanday uzatilgani.
 *
 * Aynan shu ikki narsa tuzatilgan edi:
 *   1. hisobot turi solishtiruvi ("7" === 7 -> har doim false);
 *   2. sanalar va author_id ning SQL matniga qo'shilishi (M4).
 */
class ReportFilterTest extends TestCase
{
    protected const AUTHOR_ID = 42;

    /**
     * @var array<int, array{sql: string, bindings: array<string, mixed>}>
     */
    protected array $queries = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->queries = [];

        $user = new User();
        $user->id = self::AUTHOR_ID;

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);

        DB::shouldReceive('select')->andReturnUsing(
            function (string $sql, array $bindings = []): array {
                $this->queries[] = ['sql' => $sql, 'bindings' => $bindings];

                // Bo'sh natija: PaginationFilter buni 0 deb hisoblashi kerak.
                return [];
            }
        );
    }

    /**
     * @param array<string, mixed> $input
     * @return PurchaseFormRequest
     */
    protected function request(array $input = []): PurchaseFormRequest
    {
        return PurchaseFormRequest::create(
            'http://localhost/api/report/purchase',
            'POST',
            $input
        );
    }

    /**
     * Ma'lumot qatorlari uchun qurilgan so'rov (birinchi chaqiruv).
     */
    protected function rowsQuery(): array
    {
        $this->assertNotEmpty($this->queries, 'Hech qanday so\'rov qurilmadi');

        return $this->queries[0];
    }

    /**
     * Umumiy sonni hisoblovchi so'rov (ikkinchi chaqiruv).
     */
    protected function totalQuery(): array
    {
        $this->assertCount(2, $this->queries, 'Ikkita so\'rov kutilgan edi');

        return $this->queries[1];
    }

    // -----------------------------------------------------------------
    // 1. Hisobot turi tanlanishi
    // -----------------------------------------------------------------

    /**
     * HTTP orqali `report_type_id` SATR sifatida keladi. Ilgari u int
     * enum qiymati bilan `===` orqali solishtirilardi, ya'ni "7" === 7
     * har doim false edi va bu shohobcha HECH QACHON bajarilmasdi.
     */
    public function test_free_books_report_is_selected_by_a_string_report_type(): void
    {
        ReportFilterByBooks::search($this->request([
            'report_type_id' => (string) ReportTypeEnum::BOOKS_FREE->value,
        ]));

        $sql = $this->rowsQuery()['sql'];

        $this->assertStringContainsString(
            'payment_type = :free',
            $sql,
            'BOOKS_FREE shohobchasi tanlanishi kerak edi'
        );
        $this->assertStringNotContainsString('payment_type != :free', $sql);
    }

    public function test_not_bought_books_report_is_selected_by_a_string_report_type(): void
    {
        ReportFilterByBooks::search($this->request([
            'report_type_id' => (string) ReportTypeEnum::BOOKS_NOT_BOUGHT->value,
        ]));

        $query = $this->rowsQuery();

        $this->assertStringContainsString('p.id not in (', $query['sql']);
        $this->assertArrayHasKey(
            'author_id_outer',
            $query['bindings'],
            'Bu shohobcha ikkinchi, alohida nomli binding qo\'shishi kerak'
        );
        $this->assertSame(self::AUTHOR_ID, $query['bindings']['author_id_outer']);
    }

    public function test_default_books_report_is_used_for_an_unknown_report_type(): void
    {
        ReportFilterByBooks::search($this->request(['report_type_id' => '999']));

        $this->assertStringContainsString('payment_type != :free', $this->rowsQuery()['sql']);
    }

    /**
     * @return array<string, array{0: string|null, 1: string}>
     */
    public static function booksSortProvider(): array
    {
        return [
            'ko\'p sotilgan -> desc' => [(string) ReportTypeEnum::BOOKS_LOT_BOUGHT->value, 'desc'],
            'boshqa tur -> asc' => [(string) ReportTypeEnum::BOOKS_LOW_BOUGHT->value, 'asc'],
            'tur berilmagan -> asc' => [null, 'asc'],
        ];
    }

    /**
     * @dataProvider booksSortProvider
     */
    public function test_books_report_sort_direction(?string $reportTypeId, string $expected): void
    {
        $input = $reportTypeId === null ? [] : ['report_type_id' => $reportTypeId];

        ReportFilterByBooks::search($this->request($input));

        $this->assertStringContainsString(
            'order by amount_sold ' . $expected,
            $this->rowsQuery()['sql']
        );
    }

    /**
     * @return array<string, array{0: string|null, 1: string}>
     */
    public static function benefitSortProvider(): array
    {
        return [
            'yuqori foyda -> desc' => [(string) ReportTypeEnum::BOOKS_LOT_BENEFIT_BOUGHT->value, 'desc'],
            'past foyda -> asc' => [(string) ReportTypeEnum::BOOKS_LOW_BENEFIT_BOUGHT->value, 'asc'],
            'ko\'p sotilgan -> desc' => [(string) ReportTypeEnum::BOOKS_LOT_BOUGHT->value, 'desc'],
            'tur berilmagan -> asc' => [null, 'asc'],
        ];
    }

    /**
     * @dataProvider benefitSortProvider
     */
    public function test_benefit_report_sort_direction(?string $reportTypeId, string $expected): void
    {
        $input = $reportTypeId === null ? [] : ['report_type_id' => $reportTypeId];

        ReportFilterByBenefit::search($this->request($input));

        $this->assertStringContainsString(
            'order by merchant_price_amount ' . $expected,
            $this->rowsQuery()['sql']
        );
    }

    // -----------------------------------------------------------------
    // 2. Qiymatlar SQL matniga emas, bindinglarga tushishi (M4)
    // -----------------------------------------------------------------

    /**
     * @return array<string, array{0: class-string}>
     */
    public static function filterProvider(): array
    {
        return [
            'benefit' => [ReportFilterByBenefit::class],
            'books' => [ReportFilterByBooks::class],
            'customer' => [ReportFilterByCustomer::class],
        ];
    }

    /**
     * @dataProvider filterProvider
     */
    public function test_dates_are_bound_and_never_written_into_the_sql(string $filter): void
    {
        $filter::search($this->request([
            'from_date' => '2026-01-01',
            'to_date' => '2026-12-31',
        ]));

        foreach ([$this->rowsQuery(), $this->totalQuery()] as $query) {
            $this->assertStringContainsString(':from_date', $query['sql']);
            $this->assertStringContainsString(':to_date', $query['sql']);

            $this->assertStringNotContainsString('2026-01-01', $query['sql']);
            $this->assertStringNotContainsString('2026-12-31', $query['sql']);

            $this->assertSame('2026-01-01', $query['bindings']['from_date']);
            $this->assertSame('2026-12-31', $query['bindings']['to_date']);
        }
    }

    /**
     * Sana maydonlari hozir `date_format:Y-m-d` bilan tekshiriladi, lekin
     * filtr o'zi ham himoyalangan bo'lishi kerak: qoida yumshatilsa yoki
     * boshqa FormRequest ishlatilsa zaiflik ochilib qolmasin.
     *
     * @dataProvider filterProvider
     */
    public function test_a_malicious_date_never_reaches_the_sql_text(string $filter): void
    {
        $payload = "2026-01-01' or '1'='1";

        $filter::search($this->request(['from_date' => $payload]));

        foreach ([$this->rowsQuery(), $this->totalQuery()] as $query) {
            $this->assertStringNotContainsString("or '1'='1", $query['sql']);
        }

        $this->assertSame($payload, $this->rowsQuery()['bindings']['from_date']);
    }

    /**
     * Massiv yuborilsa sana umuman qo'shilmasligi kerak (TypeError emas).
     *
     * @dataProvider filterProvider
     */
    public function test_a_non_scalar_date_is_ignored(string $filter): void
    {
        $filter::search($this->request(['from_date' => ['a' => 'b']]));

        $this->assertStringNotContainsString(':from_date', $this->rowsQuery()['sql']);
        $this->assertArrayNotHasKey('from_date', $this->rowsQuery()['bindings']);
    }

    /**
     * @dataProvider filterProvider
     */
    public function test_author_id_is_bound_not_interpolated(string $filter): void
    {
        $filter::search($this->request());

        $query = $this->rowsQuery();

        $this->assertStringContainsString(':author_id', $query['sql']);
        $this->assertSame(self::AUTHOR_ID, $query['bindings']['author_id']);
        $this->assertStringNotContainsString('= ' . self::AUTHOR_ID, $query['sql']);
    }

    /**
     * Ilgari umumiy sonni hisoblovchi so'rov `DB::select($totalQuery)`
     * ko'rinishida, YA'NI BINDINGSIZ chaqirilardi.
     *
     * @dataProvider filterProvider
     */
    public function test_the_total_query_receives_the_same_bindings(string $filter): void
    {
        $filter::search($this->request(['from_date' => '2026-01-01']));

        $rows = $this->rowsQuery()['bindings'];
        $total = $this->totalQuery()['bindings'];

        foreach (['free', 'author_id', 'from_date'] as $key) {
            $this->assertArrayHasKey($key, $total, "`{$key}` total so'rovida ham bo'lishi kerak");
            $this->assertSame($rows[$key], $total[$key]);
        }
    }
}
