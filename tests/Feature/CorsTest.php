<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Covers config/cors.php + Illuminate\Http\Middleware\HandleCors.
 *
 * Laravel is now the single owner of the CORS headers (the duplicated nginx
 * headers are removed separately), so a wrong origin list is directly visible
 * to browsers.
 */
class CorsTest extends TestCase
{
    private const ALLOWED = 'https://allowed.example';
    private const ALLOWED_SECOND = 'https://admin.allowed.example';
    private const DISALLOWED = 'https://evil.example';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'cors.paths'                    => ['api/*'],
            'cors.allowed_methods'          => ['*'],
            // Two entries on purpose: fruitcake/php-cors short circuits a
            // single-entry list into a static Access-Control-Allow-Origin
            // header, which never exercises the origin matching branch.
            'cors.allowed_origins'          => [self::ALLOWED, self::ALLOWED_SECOND],
            'cors.allowed_origins_patterns' => [],
            'cors.allowed_headers'          => ['*'],
            'cors.exposed_headers'          => [],
            'cors.max_age'                  => 3600,
            'cors.supports_credentials'     => false,
        ]);

        Route::get('api/__test/cors', fn () => response()->json(['ok' => true]));
    }

    public function test_allowed_origin_receives_the_allow_origin_header(): void
    {
        $response = $this->call('GET', '/api/__test/cors', [], [], [], [
            'HTTP_ORIGIN' => self::ALLOWED,
        ]);

        $response->assertOk();
        $this->assertSame(
            self::ALLOWED,
            $response->headers->get('Access-Control-Allow-Origin')
        );
        $this->assertStringContainsString('Origin', (string) $response->headers->get('Vary'));
    }

    public function test_disallowed_origin_does_not_receive_the_allow_origin_header(): void
    {
        $response = $this->call('GET', '/api/__test/cors', [], [], [], [
            'HTTP_ORIGIN' => self::DISALLOWED,
        ]);

        $this->assertNull($response->headers->get('Access-Control-Allow-Origin'));
    }

    public function test_preflight_from_allowed_origin_is_answered(): void
    {
        $response = $this->call('OPTIONS', '/api/__test/cors', [], [], [], [
            'HTTP_ORIGIN'                        => self::ALLOWED,
            'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'GET',
        ]);

        $this->assertSame(204, $response->getStatusCode());
        $this->assertSame(
            self::ALLOWED,
            $response->headers->get('Access-Control-Allow-Origin')
        );
        $this->assertNotNull($response->headers->get('Access-Control-Allow-Methods'));
    }

    public function test_preflight_from_disallowed_origin_gets_no_allow_origin_header(): void
    {
        $response = $this->call('OPTIONS', '/api/__test/cors', [], [], [], [
            'HTTP_ORIGIN'                        => self::DISALLOWED,
            'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'GET',
        ]);

        $this->assertNull($response->headers->get('Access-Control-Allow-Origin'));
    }

    public function test_credentials_are_not_enabled(): void
    {
        $response = $this->call('GET', '/api/__test/cors', [], [], [], [
            'HTTP_ORIGIN' => self::ALLOWED,
        ]);

        $this->assertNull($response->headers->get('Access-Control-Allow-Credentials'));
    }

    /**
     * S8: guards against the original '*, *, *, *, *' single malformed string
     * coming back, and against an accidental wildcard being shipped.
     */
    public function test_shipped_config_contains_no_malformed_or_wildcard_origin(): void
    {
        $shipped = require config_path('cors.php');

        $this->assertIsArray($shipped['allowed_origins']);
        $this->assertNotEmpty($shipped['allowed_origins']);

        foreach ($shipped['allowed_origins'] as $origin) {
            $this->assertIsString($origin);
            $this->assertStringNotContainsString(',', $origin);
            $this->assertNotSame('*', $origin);
            $this->assertNotSame('', trim($origin));
        }

        $this->assertFalse($shipped['supports_credentials']);
    }
}
