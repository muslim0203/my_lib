<?php

namespace App\Core\Services\Auth;

use App\Core\Enums\Auth\LoginTypeEnum;
use App\Core\Enums\Users\UserStatusEnum;
use App\Core\Helpers\Transaction;
use App\Core\Repository\User\SocialUserRepository;
use App\Core\Repository\User\UserRepository;
use App\Core\Repository\User\UsersVerifyTokenMailRepository;
use App\Core\Services\Auth\Interfaces\RegisterInterface;
use App\Events\VerifyMailEvent;
use App\Http\Requests\Auth\RegisterByEmailRequest;
use App\Http\Requests\Auth\VerifyMailTokenRequest;
use App\Models\Users\SocialUser;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterService implements RegisterInterface
{
    public function __construct(
        protected Transaction                    $transaction,
        protected UserRepository                 $userRepository,
        protected SocialUserRepository           $socialUserRepository,
        protected UsersVerifyTokenMailRepository $usersVerifyTokenMailRepository
    )
    {
    }

    /**
     * @param RegisterByEmailRequest $registerByEmailRequest
     * @return int
     */
    public function register(RegisterByEmailRequest $registerByEmailRequest): int
    {
        $user = $this->userRepository->findByEmail($registerByEmailRequest->post('email'));

        if (empty($user)) {
            $user = new User();
            $user->setPassword($registerByEmailRequest->post('password'));
            $user->setEmail($registerByEmailRequest->post('email'));
            $user->setLoginType(LoginTypeEnum::_LOGIN_EMAIL->value);
            $user->setStatus(UserStatusEnum::_UN_CONFIRMED->value);

            $socialUser = new SocialUser();
            $socialUser->setFirstName($registerByEmailRequest->post('first_name'));
            $socialUser->setLastName($registerByEmailRequest->post('last_name'));
            $socialUser->setMiddleName($registerByEmailRequest->post('middle_name'));
        } else {
            if ($user->isActive()) {
                abort(400, __('client.User is active'));
            }

            if ($user->isDeleted()) {
                abort(400, __('client.User is deleted'));
            }

            $socialUser = $user->socialUser;
        }

        $this->transaction->wrap(function () use ($user, $socialUser) {
            $this->socialUserRepository->save($socialUser);

            $user->setSocialUserId($socialUser->getId());
            $this->userRepository->save($user);

            VerifyMailEvent::dispatch($user);
        });

        return $user->getId();
    }

    /**
     * @param FormRequest $formRequest
     * @return bool
     */
    public function sendTokenToMail(FormRequest $formRequest): bool
    {
        $user = $this->userRepository->findByEmail($formRequest->post('email'));

        if (empty($user)) {
            $user = new User();
            $user->setEmail($formRequest->post('email'));
            $user->setUsername($formRequest->post('email'));
            $user->setLoginType(LoginTypeEnum::_LOGIN_EMAIL->value);

            $this->transaction->wrap(function () use ($user) {
                $this->userRepository->save($user);

                $socialUser = new SocialUser();
                $socialUser->setFirstName('user-' . $user->getId());
                $socialUser->setLastName('user-' . $user->getId());
                $socialUser->setMiddleName('user-' . $user->getId());
                $this->socialUserRepository->save($socialUser);

                $user->setSocialUserId($socialUser->getId());
                $this->userRepository->save($user);
            });
        } else {
            if (!$user->isActive()) {
                abort(401, __('client.Unauthorized. User is not active'));
            }

            if ($user->isEmptySocialUser()) {
                abort(400, __('client.Unauthorized. User is empty'));
            }
        }

        VerifyMailEvent::dispatch($user);

        return true;
    }

    /**
     * @param VerifyMailTokenRequest $verifyMailTokenRequest
     * @return int
     */
    public function verifyMail(VerifyMailTokenRequest $verifyMailTokenRequest): int
    {
        $user = $this->userRepository->findByEmail($verifyMailTokenRequest->post('email'));

        if (empty($user)) {
            abort(400, __('client.User is not found'));
        }

        $userVerifyTokenMail = $this->usersVerifyTokenMailRepository->getByTokenAndUserId(
            $user->getId(),
            (string)$verifyMailTokenRequest->post('token')
        );

        if (!$userVerifyTokenMail->isExpiredToken()) {
            abort(400, __('client.Token is expired'));
        }

        $user = $userVerifyTokenMail->user;

        if (empty($user)) {
            abort(404, __('client.User is not found'));
        }

        $user->setStatus(UserStatusEnum::_ACTIVE->value);
        $this->userRepository->save($user);

        return $user->getId();
    }
}
