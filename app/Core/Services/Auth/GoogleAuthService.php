<?php

namespace App\Core\Services\Auth;

use App\Core\Enums\Auth\GuardsEnum;
use App\Core\Enums\Auth\LoginTypeEnum;
use App\Core\Enums\Auth\ProvidersEnum;
use App\Core\Helpers\Transaction;
use App\Core\Repository\User\SocialiteLoginRepository;
use App\Core\Repository\User\SocialUserRepository;
use App\Core\Repository\User\UserRepository;
use App\Core\Services\Auth\Interfaces\AuthInterface;
use App\Models\Users\SocialiteLogin;
use App\Models\Users\SocialUser;
use App\Models\Users\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Tymon\JWTAuth\JWTGuard;

class GoogleAuthService implements AuthInterface
{
    public function __construct(
        protected SocialiteLoginRepository $socialiteLoginRepository,
        protected SocialUserRepository     $socialUserRepository,
        protected UserRepository           $userRepository,
        protected Transaction              $transaction
    )
    {
    }

    /**
     * @param FormRequest $formRequest
     * @return string
     */
    public function login(FormRequest $formRequest): string
    {
        try {
            /**
             * @var GoogleProvider $googleProvider
             */
            $googleProvider = Socialite::driver('google');

            $googleUser = $googleProvider->stateless()->user();

        } catch (ClientException $e) {
            Log::error($e->getMessage(), ['Invalidate credentials provided by google']);
            abort(401, __('client.Invalid credentials provided.'));
        }

        $user = $this->userRepository->findByEmail($googleUser->getEmail());


        if (!empty($user)) {
            if (!$user->isActive()) {
                abort(401, __('client.User is not active'));
            }

            if ($user->isEmptySocialUser()) {
                abort(401, __('client.User is not connect to social user'));
            }

            $socialiteLogin = $this->socialiteLoginRepository->findByUserIdAndProviderId($user->getId(), $googleUser->getId());

            if (empty($socialiteLogin)) {
                $socialiteLogin = new SocialiteLogin();
                $socialiteLogin->setUserId($user->getId());
                $socialiteLogin->setProvider(ProvidersEnum::_GOOGLE->value);
                $socialiteLogin->setProviderId($googleUser->getId());
                $this->socialiteLoginRepository->save($socialiteLogin);
            }

        } else {
            $user = new User();
            $user->setEmail($googleUser->getEmail());
            $user->setLoginType(LoginTypeEnum::_LOGIN_GOOGLE->value);

            if (empty($googleUser->user)) {
                abort(401, __('client.Google user is not found'));
            }

            $socialUser = new SocialUser();
            $socialUser->setFirstName($googleUser->user['given_name']);
            $socialUser->setLastName($googleUser->user['family_name']);
            $socialUser->setMiddleName($googleUser->user['given_name'] . ' ' . $googleUser->user['family_name']);
            $socialUser->setExternalPicture($googleUser->user['picture']);

            $this->transaction->wrap(function () use ($user, $socialUser, $googleUser) {
                $this->socialUserRepository->save($socialUser);

                $user->setSocialUserId($socialUser->getId());
                $this->userRepository->save($user);

                $socialiteLogin = new SocialiteLogin();
                $socialiteLogin->setUserId($user->getId());
                $socialiteLogin->setProvider(ProvidersEnum::_GOOGLE->value);
                $socialiteLogin->setProviderId($googleUser->getId());
                $this->socialiteLoginRepository->save($socialiteLogin);
            });
        }

        $guard = Auth::guard(GuardsEnum::_GOOGLE->value);

        if (!($guard instanceof JWTGuard)) {
            abort(401, __('client.Auth is not supported JWTGuard'));
        }

        $token = $guard->attempt([
            'email' => $user->getEmail(),
            'provider_id' => $googleUser->getId(),
        ]);

        if ($token === false) {
            abort(401, __('client.Authentication login failed'));
        }

        return (string)$token;
    }
}
