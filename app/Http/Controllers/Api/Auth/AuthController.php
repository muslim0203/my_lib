<?php

namespace App\Http\Controllers\Api\Auth;

use App\Core\Enums\Auth\LoginTypeEnum;
use App\Core\Helpers\Response\Success;
use App\Core\Services\Auth\AuthenticationService;
use App\Core\Services\Auth\Interfaces\RegisterInterface;
use App\Http\Requests\Auth\EmailSendCodeRequest;
use App\Http\Requests\Auth\LoginByEmailRequest;
use App\Http\Requests\Auth\LoginByGoogleRequest;
use App\Core\Services\Auth\GoogleOAuthState;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginBySmsRequest;
use App\Http\Requests\Auth\RegisterByEmailRequest;
use App\Http\Requests\Auth\VerifyMailTokenRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(
        protected AuthenticationService $authenticationService
    )
    {
    }

    /**
     * @param LoginByEmailRequest $loginByEmailRequest
     * @return JsonResponse
     */
    public function loginByEmail(LoginByEmailRequest $loginByEmailRequest): JsonResponse
    {
        return Success::send('Successful login by email', [
            'type' => 'Bearer',
            'access_token' => $this->authenticationService->login($loginByEmailRequest, LoginTypeEnum::_LOGIN_EMAIL->value)
        ]);
    }

    public function loginBySms(LoginBySmsRequest $loginBySmsRequest)
    {
        $this->authenticationService->login($loginBySmsRequest, LoginTypeEnum::_LOGIN_SMS->value);
    }

    /**
     * @param FormRequest $formRequest
     * @return JsonResponse
     */
    public function loginByGoogle(LoginByGoogleRequest $formRequest): JsonResponse
    {
        return Success::send('Successful done login by google', [
            'type' => 'Bearer',
            'access_token' => $this->authenticationService->login($formRequest, LoginTypeEnum::_LOGIN_GOOGLE->value)
        ])->header('Cache-Control', 'no-store');
    }

    /**
     * @return JsonResponse
     */
    public function redirectToAuthByGoogle(Request $request, GoogleOAuthState $oauthState): JsonResponse
    {
        foreach (['client_id', 'client_secret', 'redirect'] as $key) {
            abort_unless(config('services.google.' . $key), 503, 'Google sign-in is not configured.');
        }
        $input = $request->validate([
            'state' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]{43}$/'],
            'code_challenge' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]{43}$/'],
        ]);
        $oauthState->issue($input['state'], $input['code_challenge']);
        /**
         * @var GoogleProvider $googleDriver
         */
        $googleDriver = Socialite::driver('google');

        return Success::send(
            'Redirect Url to auth by google',
            [
                'url' => $googleDriver
                    ->stateless()
                    ->with([
                        'state' => $input['state'],
                        'code_challenge' => $input['code_challenge'],
                        'code_challenge_method' => 'S256',
                        'prompt' => 'select_account',
                    ])
                    ->redirect()
                    ->getTargetUrl()
            ]
        )->header('Cache-Control', 'no-store');
    }

    /*public function register(
        RegisterByEmailRequest $registerByEmailRequest,
        RegisterInterface      $registerService
    ): JsonResponse
    {
        return Success::send('Send token to email verify', ['id' => $registerService->register($registerByEmailRequest)]);
    }*/


    /**
     * @param EmailSendCodeRequest $emailSendCodeRequest
     * @param RegisterInterface $registerService
     * @return JsonResponse
     */
    public function sendTokenToMail(EmailSendCodeRequest $emailSendCodeRequest, RegisterInterface $registerService): JsonResponse
    {
        $registerService->sendTokenToMail($emailSendCodeRequest);

        return Success::send('Send token to email verify');
    }


    /*public function verifyMail(
        VerifyMailTokenRequest $verifyMailTokenRequest,
        RegisterInterface      $registerService
    ): JsonResponse
    {
        return Success::send('Verify email', ['id' => $registerService->verifyMail($verifyMailTokenRequest)]);
    }*/

    /**
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        return Success::send('Generated new Access Token', [
            'type' => 'Bearer',
            'access_token' => $this->authenticationService->refreshToken()
        ]);
    }

    public function logout()
    {
        if (!Auth::check()) {
            abort(401, __('client.User is not found'));
        }

        try {
            // Adds token to blacklist.
            JWTAuth::parseToken()->invalidate(true);

            return Success::send('Successfully logged out');
        } catch (TokenExpiredException|TokenInvalidException|JWTException $exception) {
            return Success::error($exception->getMessage());
        }
    }
}
