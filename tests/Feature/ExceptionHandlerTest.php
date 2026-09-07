<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Tests\TestCase;

/**
 * Covers App\Exceptions\Handler.
 *
 * The routes below are registered inside the test (routes/api.php is owned by
 * another change), but they go through the very same global middleware stack
 * and the very same exception handler as production traffic.
 */
class ExceptionHandlerTest extends TestCase
{
    private const SECRET = 'SQLSTATE[42P01] relation "top_secret_table" does not exist';

    protected function setUp(): void
    {
        parent::setUp();

        Route::post('api/__test/validation', function () {
            throw ValidationException::withMessages([
                'email' => ['The email field is required.'],
                'code'  => ['The code field must be 4 digits.'],
            ]);
        });

        Route::get('api/__test/boom', function () {
            throw new RuntimeException(self::SECRET);
        });

        Route::get('api/__test/unauthenticated', function () {
            throw new AuthenticationException();
        });

        Route::get('api/__test/model-missing', function () {
            throw (new ModelNotFoundException())->setModel(\App\Models\Users\User::class);
        });
    }

    /**
     * S7: ValidationException used to be answered with HTTP 500 and an empty
     * `data` key. It must now be 422 and carry the per field errors.
     */
    public function test_validation_exception_returns_422_with_per_field_errors(): void
    {
        $response = $this->postJson('/api/__test/validation');

        $response->assertStatus(422);
        $response->assertJsonPath('status', false);
        $response->assertJsonPath('data.errors.email.0', 'The email field is required.');
        $response->assertJsonPath('data.errors.code.0', 'The code field must be 4 digits.');
    }

    /**
     * The successful response envelope (status/message/code/data) is unchanged.
     */
    public function test_error_envelope_keys_are_preserved(): void
    {
        $response = $this->getJson('/api/__test/boom');

        $response->assertJsonStructure(['status', 'message', 'code', 'data']);
        $response->assertJsonPath('status', false);
    }

    /**
     * S7: with APP_DEBUG=false the raw exception message must never reach the
     * client, regardless of APP_ENV (the old guard was on APP_ENV only).
     */
    public function test_unexpected_exception_does_not_leak_message_when_debug_is_off(): void
    {
        config(['app.debug' => false, 'app.env' => 'local']);

        $response = $this->getJson('/api/__test/boom');

        $response->assertStatus(500);
        $response->assertDontSee('top_secret_table');
        $this->assertStringNotContainsString(self::SECRET, $response->getContent());
    }

    /**
     * The developer experience must not regress: with APP_DEBUG=true the real
     * message is still returned.
     */
    public function test_unexpected_exception_is_visible_when_debug_is_on(): void
    {
        config(['app.debug' => true]);

        $response = $this->getJson('/api/__test/boom');

        $response->assertStatus(500);
        $this->assertStringContainsString('top_secret_table', $response->getContent());
    }

    /**
     * S7: $statusCode was an instance property on a singleton handler, so a
     * 404 from one request leaked into the next response.
     */
    public function test_status_code_does_not_leak_between_requests(): void
    {
        config(['app.debug' => false]);

        $this->getJson('/api/__test/model-missing')->assertStatus(404);
        $this->getJson('/api/__test/boom')->assertStatus(500);
    }

    /**
     * S7: ModelNotFoundException must not disclose the Eloquent class name
     * when debugging is disabled.
     */
    public function test_model_not_found_does_not_leak_class_name_when_debug_is_off(): void
    {
        config(['app.debug' => false]);

        $response = $this->getJson('/api/__test/model-missing');

        $response->assertStatus(404);
        $this->assertStringNotContainsString('User', $response->getContent());
    }

    /**
     * S7: AuthenticationException used to fall through to 500.
     */
    public function test_authentication_exception_returns_401(): void
    {
        $this->getJson('/api/__test/unauthenticated')->assertStatus(401);
    }

    /**
     * S7: an unmatched api route has a null route prefix, so the old detection
     * produced an HTML error page for an API 404.
     */
    public function test_unmatched_api_route_returns_json_404(): void
    {
        $response = $this->getJson('/api/__test/this-route-does-not-exist');

        $response->assertStatus(404);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonStructure(['status', 'message', 'code', 'data']);
    }

    /**
     * S7: "application/json; charset=utf-8" did not match the old exact string
     * comparison on the Content-Type header.
     */
    public function test_content_type_with_charset_is_treated_as_json(): void
    {
        $response = $this->call(
            'POST',
            '/api/__test/validation',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json; charset=utf-8']
        );

        $response->assertStatus(422);
        $response->assertJsonPath('data.errors.email.0', 'The email field is required.');
    }

    /**
     * Smoke test on a real, unauthenticated API route from routes/api.php.
     *
     * App\Http\Requests\Auth\EmailSendCodeRequest uses the
     * App\Core\Helpers\Requests\ValidationException trait, so it answers 422
     * through App\Core\Helpers\Response\Validation instead of reaching the
     * handler. The assertion checks that a real API route reports validation
     * failures with 422 and per field details either way.
     */
    public function test_real_api_route_returns_422_with_field_details(): void
    {
        $response = $this->postJson('/api/auth/send-token-to-mail', []);

        $response->assertStatus(422);
        $this->assertStringContainsString('email', $response->getContent());
    }

    /**
     * A validation failure produced by the framework validator (not by a
     * FormRequest that overrides failedValidation) goes through the handler.
     */
    public function test_manual_validator_failure_is_reported_per_field(): void
    {
        Route::post('api/__test/manual-validation', function () {
            Validator::make([], ['email' => 'required|email'])->validate();
        });

        $response = $this->postJson('/api/__test/manual-validation');

        $response->assertStatus(422);
        $response->assertJsonStructure(['data' => ['errors' => ['email']]]);
    }
}
