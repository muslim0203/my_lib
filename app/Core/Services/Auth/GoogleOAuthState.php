<?php

namespace App\Core\Services\Auth;

use Illuminate\Support\Facades\Cache;

class GoogleOAuthState
{
    public function issue(string $state, string $challenge): void
    {
        abort_unless(Cache::add($this->key($state), $challenge, now()->addMinutes(10)), 422, 'OAuth state already exists.');
    }

    public function consume(string $state, string $verifier): void
    {
        $key = $this->key($state);
        $expected = Cache::get($key);
        $actual = rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
        abort_unless(is_string($expected) && hash_equals($expected, $actual), 401, 'Invalid or expired OAuth attempt.');
        // Atomic add prevents concurrent exchanges from consuming the same attempt.
        abort_unless(Cache::add($key . ':used', true, now()->addMinutes(10)), 401, 'OAuth attempt already used.');
        Cache::forget($key);
    }

    private function key(string $state): string
    {
        return 'google-oauth:' . hash('sha256', $state);
    }
}
