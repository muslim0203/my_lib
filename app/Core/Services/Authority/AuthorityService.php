<?php

namespace App\Core\Services\Authority;

use App\Core\Enums\Authors\AuthorStatusEnum;
use App\Core\Helpers\Transaction;
use App\Core\Repository\Author\AuthorRequestRepository;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Repository\Merchant\MerchantRepository;
use App\Core\Repository\Notification\NotificationMessageRepository;
use App\Core\Repository\Notification\NotificationRepository;
use App\Core\Services\Authority\Contract\AuthorityContract;
use App\Http\Requests\Authors\ConfirmOrCancelRequest;
use App\Models\Authority\Authority;
use App\Models\Authors\AuthorRequest;
use App\Models\Notifications\Notification;
use App\Models\Users\Merchant;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;

class AuthorityService implements AuthorityContract
{
    public function __construct(
        protected AuthorityRepository     $authorityRepository,
        protected Transaction             $transaction,
        protected MerchantRepository      $merchantRepository,
        protected NotificationRepository  $notificationRepository,
        protected AuthorRequestRepository $authorRequestRepository,
    )
    {
    }

    public function confirmOrCancel(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id, bool $isConfirm = true): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $authority = $this->authorityRepository->findByUserId($id);
        $authority->setStatus($isConfirm ? AuthorStatusEnum::_ACTIVE->value : AuthorStatusEnum::_CANCELED->value);
        $authority->setStepId($isConfirm ? 3 : 2);

        $authorRequest = new AuthorRequest();
        $authorRequest->setDescription($confirmOrCancelRequest->post('description'));
        $authorRequest->setCreatedBy($user->getId());
        $authorRequest->setIsConfirmed($isConfirm);
        $authorRequest->setModel(Authority::class);

        /**
         * @var NotificationMessageRepository $notificationMessageRepository
         */
        $notificationMessageRepository = app(NotificationMessageRepository::class);
        $notificationMessage = $notificationMessageRepository->getByTypeId($isConfirm ? 4 : 3);

        $notification = new Notification();
        $notification->setNotificationTypeId($isConfirm ? 4 : 3);
        $notification->setNotificationMessageId($notificationMessage->getId());
        $notification->setMessageOz(__($notificationMessage->getMessageOz(), ['id' => $authority->getId()]));
        $notification->setMessageUz(__($notificationMessage->getMessageUz(), ['id' => $authority->getId()]));
        $notification->setMessageRu(__($notificationMessage->getMessageRu(), ['id' => $authority->getId()]));
        $notification->setModel(Authority::class);

        $this->transaction->wrap(function () use ($authority, $isConfirm, $authorRequest, $notification) {
            if ($isConfirm) {
                $merchant = new Merchant();
                $merchant->setUserId($authority->getUserId());
                $merchant->setModelId($authority->getId());
                $merchant->setModelType(Authority::class);
                $this->merchantRepository->save($merchant);
            }

            $this->authorityRepository->save($authority);

            $authorRequest->setModelId($authority->getId());
            $this->authorRequestRepository->save($authorRequest);

            $notification->setApplyId((string)$authority->getId());
            $this->notificationRepository->save($notification);

        });

        return true;
    }
}
