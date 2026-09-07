<?php

namespace App\Core\Services\User;

use App\Core\Helpers\Transaction;
use App\Core\Repository\User\SocialUserRepository;
use App\Core\Repository\User\UserInterestRepository;
use App\Core\Repository\User\UserRepository;
use App\Core\Services\User\Interfaces\UserInterface;
use App\Http\Requests\User\SelectInterestRequest;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Resources\User\UserViewResource;
use App\Models\Enums\EnumProductGenre;
use App\Models\Enums\EnumProductType;
use App\Models\Users\User;
use App\Models\Users\UserInterest;
use Illuminate\Support\Facades\Auth;

class UserService implements UserInterface
{
    public function __construct(
        protected UserRepository         $userRepository,
        protected SocialUserRepository   $socialUserRepository,
        protected UserInterestRepository $userInterestRepository
    )
    {
    }

    /**
     * @param UserEditRequest $userEditRequest
     * @return int
     */
    public function edit(UserEditRequest $userEditRequest): int
    {
        if (!Auth::check()) {
            abort(401, __('client.Authorization error. User is not found'));
        }

        /**
         * @var User $authUser
         */
        $authUser = Auth::user();

        $user = $this->userRepository->get($authUser->getId());

        $socialUser = $user->socialUser;

        if (is_null($socialUser)) {
            abort(404, __('client.Social user is not found'));
        }

        $socialUser->fill($userEditRequest->validated());

        $this->socialUserRepository->save($socialUser);

        return $user->getId();
    }

    /**
     * @return UserViewResource
     */
    public function list(): UserViewResource
    {
        if (!Auth::check()) {
            abort(401, __('client.Authorization error. User is not found'));
        }

        /**
         * @var User $authUser
         */
        $authUser = Auth::user();

        $user = $this->userRepository->get($authUser->getId());

        if ($user->isEmptySocialUser()) {
            abort(404, __('client.Social user is not found'));
        }

        return new UserViewResource($user);
    }

    /**
     * @param SelectInterestRequest $selectInterestRequest
     * @return bool
     */
    public function selectInterest(SelectInterestRequest $selectInterestRequest): bool
    {

        if (empty($selectInterestRequest->post('types')) && empty($selectInterestRequest->post('genres'))) {
            return false;
        }

        /**
         * @var Transaction $transaction
         */
        $transaction = app(Transaction::class);

        $transaction->wrap(function () use ($selectInterestRequest) {

            /**
             * @var User $user
             */
            $user = Auth::user();

            $this->userInterestRepository->removeByUser($user->getId());

            foreach ($selectInterestRequest->post('types') as $item) {
                $model = new UserInterest();
                $model->setUserId($user->getId());
                $model->setModelId((int)$item);
                $model->setModelType(EnumProductType::class);
                $this->userInterestRepository->save($model);
            }

            foreach ($selectInterestRequest->post('genres') as $item) {
                $model = new UserInterest();
                $model->setUserId($user->getId());
                $model->setModelId((int)$item);
                $model->setModelType(EnumProductGenre::class);
                $this->userInterestRepository->save($model);
            }

        });

        return true;
    }

    /**
     * @return array
     */
    public function interestList(): array
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        return $user->interests->all();
    }
}
