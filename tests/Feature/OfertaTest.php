<?php

namespace Tests\Feature;

use Tests\TestCase;

class OfertaTest extends TestCase
{
    public static function locales(): array
    {
        return [
            'lotin' => ['oz', 'VOSITACHILIK SHARTNOMASI (OFERTA)', 'AJ AND J GROUP'],
            'kirill' => ['uz', 'ВОСИТАЧИЛИК ШАРТНОМАСИ (ОФЕРТА)', 'Якуний қоидалар'],
            'rus' => ['ru', 'ПОСРЕДНИЧЕСКИЙ ДОГОВОР (ОФЕРТА)', 'Заключительные положения'],
        ];
    }

    /**
     * @dataProvider locales
     */
    public function test_public_page_renders_without_login(string $locale, string $title, string $text): void
    {
        $this->get('/oferta/' . $locale)
            ->assertOk()
            ->assertSee($title)
            ->assertSee($text)
            ->assertSee('311282221');
    }

    public function test_unknown_locale_is_404(): void
    {
        $this->get('/oferta/xx')->assertNotFound();
        $this->get('/oferta/..%2F..%2F.env')->assertNotFound();
    }

    public function test_page_without_locale_uses_default(): void
    {
        $this->get('/oferta')->assertOk()->assertSee('VOSITACHILIK SHARTNOMASI (OFERTA)');
    }

    /**
     * @dataProvider locales
     */
    public function test_api_returns_offer_for_accept_language(string $locale, string $title, string $text): void
    {
        $this->getJson('/api/oferta', ['Accept-Language' => $locale])
            ->assertOk()
            ->assertJsonPath('data.lang', $locale)
            ->assertJsonPath('data.title', $title)
            ->assertJsonPath('data.version', '2026-10-16')
            ->assertJsonPath('data.url', url('/oferta/' . $locale));

        $this->assertStringContainsString($text, $this->getJson('/api/oferta', ['Accept-Language' => $locale])->json('data.content'));
    }
}
