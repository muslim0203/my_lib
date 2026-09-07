<?php

namespace App\Core\Services\Auth;


use App\Core\Enums\Auth\GuardsEnum;
use App\Core\Enums\Auth\LoginTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTGuard;

class AuthenticationService
{
    public function __construct(
        protected LoginAndPasswordService $loginAndPasswordService,
        protected GoogleAuthService       $googleAuthService,
        protected EmailAuthService        $emailAuthService,
        protected SmsAuthService          $smsAuthService
    )
    {
    }

    public function login(FormRequest $formRequest, string $login_type)
    {
        $authService = match ($login_type) {
            LoginTypeEnum::_LOGIN_EMAIL->value => new AuthGateway($this->emailAuthService),
            LoginTypeEnum::_LOGIN_GOOGLE->value => new AuthGateway($this->googleAuthService),
            LoginTypeEnum::_LOGIN_SMS->value => new AuthGateway($this->smsAuthService),
            LoginTypeEnum::_LOGIN_LOGIN_PASS->value => new AuthGateway($this->loginAndPasswordService),
            default => null
        };

        if (is_null($authService)) {
            abort(400, __('client.Unknown login type'));
        }

        return $authService->login($formRequest);
    }

    /**
     * @return string
     */
    public function refreshToken(): string
    {
        $guard = Auth::guard(GuardsEnum::_MAIL->value);

        if (!($guard instanceof JWTGuard)) {
            abort(401, __('client.Auth is not supported JWTGuard'));
        }

        try {
            return $guard->refresh();
        } catch (\Exception $e) {
            abort(401, $e->getMessage());
        }
    }
}
