<?php

namespace App\Core\Services\Author;

use App\Core\Enums\Authors\AuthorStatusEnum;
use App\Core\Helpers\Transaction;
use App\Core\Repository\Author\AuthorProfileFileRepository;
use App\Core\Repository\Author\AuthorRepository;
use App\Core\Repository\Author\AuthorRequestRepository;
use App\Core\Repository\Merchant\MerchantRepository;
use App\Core\Repository\Notification\NotificationMessageRepository;
use App\Core\Repository\Notification\NotificationRepository;
use App\Core\Services\Author\Interfaces\AuthorInterface;
use App\Http\Requests\Authors\AuthorEditRequest;
use App\Http\Requests\Authors\ConfirmOrCancelRequest;
use App\Models\Authors\Author;
use App\Models\Authors\AuthorRequest;
use App\Models\Notifications\Notification;
use App\Models\Users\Merchant;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;

class AuthorService implements AuthorInterface
{
    public function __construct(
        protected AuthorRequestRepository     $authorRequestRepository,
        protected AuthorProfileFileRepository $authorProfileFileRepository,
        protected NotificationRepository      $notificationRepository,
        protected MerchantRepository          $merchantRepository,
        protected AuthorRepository            $authorRepository,
        protected Transaction                 $transaction
    )
    {
    }

    public function edit(AuthorEditRequest $authorEditRequest): int
    {
        return 0;
    }

    /**
     * @param ConfirmOrCancelRequest $confirmOrCancelRequest
     * @param int $id
     * @param bool $isConfirm
     * @return bool
     */
    public function confirmOrCancel(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id, bool $isConfirm = true): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $author = $this->authorProfileFileRepository->get($id);

        $author->setStepId($isConfirm ? 3 : 2);
        $author->setStatus($isConfirm ? AuthorStatusEnum::_ACTIVE->value : AuthorStatusEnum::_CANCELED->value);

        $authorRequest = new AuthorRequest();
        $authorRequest->setDescription($confirmOrCancelRequest->post('description'));
        $authorRequest->setCreatedBy($user->getId());
        $authorRequest->setIsConfirmed($isConfirm);
        $authorRequest->setModel(Author::class);

        /**
         * @var NotificationMessageRepository $notificationMessageRepository
         */
        $notificationMessageRepository = app(NotificationMessageRepository::class);
        $notificationMessage = $notificationMessageRepository->getByTypeId($isConfirm ? 4 : 3);

        $notification = new Notification();
        $notification->setNotificationTypeId($isConfirm ? 4 : 3);
        $notification->setNotificationMessageId($notificationMessage->getId());
        $notification->setMessageOz(__($notificationMessage->getMessageOz(), ['id' => $author->getId()]));
        $notification->setMessageUz(__($notificationMessage->getMessageUz(), ['id' => $author->getId()]));
        $notification->setMessageRu(__($notificationMessage->getMessageRu(), ['id' => $author->getId()]));
        $notification->setModel(Author::class);

        $this->transaction->wrap(function () use ($author, $authorRequest, $notification, $isConfirm) {
            if ($isConfirm) {
                $merchant = new Merchant();
                $merchant->setUserId($author->getUserId());
                $merchant->setModelId($author->getId());
                $merchant->setModelType(Author::class);
                $this->merchantRepository->save($merchant);
            }

            $this->authorRepository->save($author);

            $authorRequest->setModelId($author->getId());
            $this->authorRequestRepository->save($authorRequest);

            $notification->setApplyId((string)$author->getId());
            $this->notificationRepository->save($notification);
        });

        return true;
    }
}
