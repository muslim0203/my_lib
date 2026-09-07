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
        $user = $this->userRepository->findByEmail($formRequest->post('email'));

        $userVerifyTokenMail = $this->usersVerifyTokenMailRepository->getByTokenAndUserId(
            $user->getId(),
            (string)$formRequest->post('code')
        );

        $guard = Auth::guard(GuardsEnum::_MAIL->value);

        if (!($guard instanceof JWTGuard)) {
            abort(401, __('client.Auth is not supported JWTGuard'));
        }

        $token = $guard->attempt($formRequest->only('email', 'code'));

        if ($token === false) {
            abort(401, __('client.Auth login failed'));
        }

        $userVerifyTokenMail->setEnabled(false);
        $this->usersVerifyTokenMailRepository->save($userVerifyTokenMail);

        return (string)$token;
    }
}
