<?php

namespace Tests\Feature;

use App\Core\Services\Legal\LegalDocumentService;
use Tests\TestCase;

class LegalDocumentTest extends TestCase
{
    public static function sellerOffer(): array
    {
        return [
            'lotin' => ['oz', 'VOSITACHILIK SHARTNOMASI (OFERTA)', 'AJ AND J GROUP'],
            'kirill' => ['uz', 'ВОСИТАЧИЛИК ШАРТНОМАСИ (ОФЕРТА)', 'Якуний қоидалар'],
            'rus' => ['ru', 'ПОСРЕДНИЧЕСКИЙ ДОГОВОР (ОФЕРТА)', 'Заключительные положения'],
        ];
    }

    public static function documentsAndLocales(): array
    {
        $cases = [];

        foreach (array_keys(LegalDocumentService::DOCUMENTS) as $document) {
            foreach (['oz', 'uz', 'ru'] as $locale) {
                $cases["$document/$locale"] = [$document, $locale];
            }
        }

        return $cases;
    }

    /**
     * @dataProvider sellerOffer
     */
    public function test_seller_offer_short_url_renders_without_login(string $locale, string $title, string $text): void
    {
        $this->get('/oferta/' . $locale)
            ->assertOk()
            ->assertSee($title)
            ->assertSee($text)
            ->assertSee('311282221');
    }

    public function test_seller_offer_without_locale_uses_default(): void
    {
        $this->get('/oferta')->assertOk()->assertSee('VOSITACHILIK SHARTNOMASI (OFERTA)');
    }

    /**
     * @dataProvider documentsAndLocales
     */
    public function test_every_document_has_a_page_in_every_language(string $document, string $locale): void
    {
        $service = new LegalDocumentService();

        $this->assertNotSame('', $service->title($document, $locale));

        $this->get("/legal/$document/$locale")
            ->assertOk()
            ->assertSee($service->title($document, $locale));
    }

    public function test_unknown_document_or_locale_is_404(): void
    {
        $this->get('/oferta/xx')->assertNotFound();
        $this->get('/oferta/..%2F..%2F.env')->assertNotFound();
        $this->get('/legal/unknown/oz')->assertNotFound();
        $this->get('/legal/..%2F..%2F.env/oz')->assertNotFound();
        $this->getJson('/api/legal/unknown')->assertNotFound();
    }

    /**
     * @dataProvider sellerOffer
     */
    public function test_api_returns_seller_offer_for_accept_language(string $locale, string $title, string $text): void
    {
        $response = $this->getJson('/api/oferta', ['Accept-Language' => $locale])
            ->assertOk()
            ->assertJsonPath('data.document', LegalDocumentService::SELLER_OFFER)
            ->assertJsonPath('data.lang', $locale)
            ->assertJsonPath('data.title', $title)
            ->assertJsonPath('data.version', LegalDocumentService::DOCUMENTS[LegalDocumentService::SELLER_OFFER])
            ->assertJsonPath('data.url', url('/legal/seller-offer/' . $locale));

        $this->assertStringContainsString($text, $response->json('data.content'));
    }

    public function test_api_lists_documents_without_content(): void
    {
        $response = $this->getJson('/api/legal', ['Accept-Language' => 'ru'])->assertOk();

        $this->assertSame(array_keys(LegalDocumentService::DOCUMENTS), array_column($response->json('data'), 'document'));
        $this->assertArrayNotHasKey('content', $response->json('data.0'));
    }

    /**
     * Uchala tilda bandlar raqamlari bir xil bo'lishi kerak: tarjimada
     * band tushib qolmasligi uchun.
     *
     * @dataProvider documents
     */
    public function test_translations_have_the_same_clauses(string $document): void
    {
        $service = new LegalDocumentService();
        $clauses = static function (string $html): array {
            preg_match_all('~<p>(\d+(?:\.\d+)+)\.~u', $html, $m);

            return $m[1];
        };

        $oz = $clauses($service->content($document, 'oz'));

        $this->assertNotEmpty($oz);
        $this->assertSame($oz, $clauses($service->content($document, 'uz')));
        $this->assertSame($oz, $clauses($service->content($document, 'ru')));
    }

    public static function documents(): array
    {
        return array_map(static fn(string $doc): array => [$doc], array_combine(
            array_keys(LegalDocumentService::DOCUMENTS),
            array_keys(LegalDocumentService::DOCUMENTS)
        ));
    }
}
