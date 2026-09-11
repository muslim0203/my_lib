<?php
namespace Tests\Feature;

use App\Core\Services\Auth\GoogleOAuthState;
use App\Core\Services\Auth\GoogleAuthService;
use App\Http\Requests\Auth\LoginByGoogleRequest;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class GoogleOAuthTest extends TestCase
{
    private function challenge(string $verifier): string
    {
        return rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
    }

    public function test_state_is_bound_to_verifier_and_consumed_once(): void
    {
        $flow = app(GoogleOAuthState::class);
        $state = str_repeat('a', 43);
        $verifier = str_repeat('b', 43);
        $flow->issue($state, $this->challenge($verifier));
        try {
            $flow->consume($state, str_repeat('c', 43));
            $this->fail('Wrong verifier accepted');
        } catch (HttpException $e) {
            $this->assertSame(401, $e->getStatusCode());
        }
        $flow->consume($state, $verifier);
        $this->expectException(HttpException::class);
        $flow->consume($state, $verifier);
    }

    public function test_expired_state_is_rejected(): void
    {
        $flow = app(GoogleOAuthState::class);
        $flow->issue(str_repeat('d', 43), $this->challenge(str_repeat('e', 43)));
        $this->travel(11)->minutes();
        $this->expectException(HttpException::class);
        $flow->consume(str_repeat('d', 43), str_repeat('e', 43));
    }

    public function test_unconfigured_google_returns_service_unavailable(): void
    {
        config(['services.google.client_id' => null]);
        $this->getJson('/api/auth/redirect-to-auth-by-google')->assertStatus(503);
    }

    public function test_redirect_contains_pkce_and_fixed_callback(): void
    {
        config(['services.google' => [
            'client_id' => 'test-client', 'client_secret' => 'test-secret',
            'redirect' => 'https://frontend.example/auth/login',
        ]]);
        $state = str_repeat('f', 43);
        $challenge = $this->challenge(str_repeat('g', 43));
        $response = $this->getJson('/api/auth/redirect-to-auth-by-google?' . http_build_query([
            'state' => $state, 'code_challenge' => $challenge,
        ]))->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        parse_str(parse_url($response->json('data.url'), PHP_URL_QUERY), $query);
        $this->assertSame($state, $query['state']);
        $this->assertSame($challenge, $query['code_challenge']);
        $this->assertSame('S256', $query['code_challenge_method']);
        $this->assertSame('https://frontend.example/auth/login', $query['redirect_uri']);
    }

    public function test_callback_requires_code_state_and_verifier(): void
    {
        $this->postJson('/api/auth/login-by-google', [])->assertStatus(422);
    }

    public function test_verifier_reaches_google_and_unverified_email_is_rejected(): void
    {
        $state = str_repeat('h', 43);
        $verifier = str_repeat('i', 43);
        app(GoogleOAuthState::class)->issue($state, $this->challenge($verifier));
        $request = LoginByGoogleRequest::create('/api/auth/login-by-google', 'POST', [
            'code' => 'test-code', 'state' => $state, 'code_verifier' => $verifier,
        ]);
        $history = [];
        $stack = HandlerStack::create(new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'test-google-token', 'token_type' => 'Bearer'])),
            new Response(200, [], json_encode(['sub' => '123', 'email' => 'test@example.com', 'email_verified' => false])),
        ]));
        $stack->push(Middleware::history($history));
        $provider = new GoogleProvider($request, 'client', 'secret', 'https://frontend.example/auth/login');
        $provider->setHttpClient(new Client(['handler' => $stack]));
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);
        try {
            app(GoogleAuthService::class)->login($request);
            $this->fail('Unverified email accepted');
        } catch (HttpException $e) {
            $this->assertSame(401, $e->getStatusCode());
        }
        parse_str((string) $history[0]['request']->getBody(), $form);
        $this->assertSame($verifier, $form['code_verifier']);
        $this->assertSame('test-code', $form['code']);
    }
}

