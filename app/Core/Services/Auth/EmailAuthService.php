<?php

namespace App\Core\Services\Auth;

use App\Core\Enums\Auth\GuardsEnum;
use App\Core\Repository\User\UserRepository;
use App\Core\Repository\User\UsersVerifyTokenMailRepository;
use App\Core\Services\Auth\Interfaces\AuthInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTGuard;

class EmailAuthService implements AuthInterface
{
    public function __construct(
        protected UserRepository                 $userRepository,
        protected UsersVerifyTokenMailRepository $usersVerifyTokenMailRepository,
    )
    {
    }

    /**
     * @param FormRequest $formRequest
     * @return string
     */
    public function login(FormRequest $formRequest): string
    {
        $guard = Auth::guard(GuardsEnum::_MAIL->value);

        if (!($guard instanceof JWTGuard)) {
            abort(401, __('client.Auth is not supported JWTGuard'));
        }

        // Kod endi hash ko'rinishida saqlanadi, shuning uchun uni
        // oldindan qidirib bo'lmaydi. Tekshirish, urinishlar hisobi va
        // bir martalik iste'mol EmailUserProvider::validateCredentials
        // ichidagi atomar amalda bajariladi.
        $token = $guard->attempt($formRequest->only('email', 'code'));

        if ($token === false) {
            // Barcha muvaffaqiyatsiz holatlar uchun bitta umumiy xato:
            // noto'g'ri kod, muddati o'tgan kod, mavjud bo'lmagan pochta
            // yoki urinishlar chegarasi - hammasi bir xil ko'rinadi.
            abort(401, __('client.Auth login failed'));
        }

        return (string)$token;
    }
}
