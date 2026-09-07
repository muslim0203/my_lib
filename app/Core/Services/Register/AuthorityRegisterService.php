<?php

namespace App\Core\Services\Register;

use App\Core\Enums\Requests\RequestStatusEnum;
use App\Core\Enums\Requests\RequestTypeEnum;
use App\Core\Enums\Steps\ProcessStepEnum;
use App\Core\Helpers\Transaction;
use App\Core\Repository\Notification\NotificationMessageRepository;
use App\Core\Repository\Notification\NotificationRepository;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Repository\Step\ProcessStepRepository;
use App\Core\Services\Register\Contracts\Register;
use App\Models\Authority\Authority;
use App\Models\Notifications\Notification;
use App\Models\Request\Request;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthorityRegisterService implements Register
{
    /**
     * @param FormRequest $formRequest
     * @param int|null $id
     * @return int
     */
    public function register(FormRequest $formRequest, ?int $id = null): int
    {
        $transaction = new Transaction();

        if (!Auth::check()) {
            abort(403, __('client.Unauthorized action.'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $requestRepository = new RequestRepository();

        $request = $requestRepository->findByAuthor($user->getId(), [
            RequestTypeEnum::_AUTHORITY->value,
            RequestTypeEnum::_AUTHOR->value
        ]);

        if (
            !empty($request)
            && in_array($request->getStatus(), [
                RequestStatusEnum::_CHECKING->value,
                RequestStatusEnum::_APPROVED->value
            ])
        ) {
            abort(400, __('client.This action is not permit.'));
        }

        $request = is_null($id) ? new Request() : $requestRepository->getOwned($id, $user->getId());

        if (!empty($id) && !$request->isIsEditable()) {
            throw new BadRequestHttpException(__('client.Request doesnt editable'));
        }

        $request->setRequestTypeId(RequestTypeEnum::_AUTHORITY->value);
        $request->setModel(Authority::class);
        $request->setAuthorId($user->getId());
        $request->setData(json_encode($formRequest->validated()));
        $request->setStatus(RequestStatusEnum::_CHECKING->value);
        $processStepModel = app(ProcessStepRepository::class);
        $request->setStepId($processStepModel->getByCodeName(ProcessStepEnum::_PROCESS_ADD_NEW_AUTHOR->value)->getId());
        $request->setIsAgree((bool)$formRequest->post('is_agree'));

        /**
         * @var NotificationMessageRepository $notificationMessage
         */
        $notificationMessage = app(NotificationMessageRepository::class);
        $message = $notificationMessage->getByTypeId(1);

        $notification = new Notification();
        $notification->setModel(Authority::class);
        $notification->setNotificationMessageId($message->getId());
        $notification->setNotificationTypeId($message->getNotificationTypeId());
        $notification->setUserId($user->getId());

        $transaction->wrap(function () use ($requestRepository, $notification, $message, $request) {

            $requestRepository->save($request);

            $notificationRepository = new NotificationRepository();
            $notification->setMessageOz(__($message->getMessageOz(), ['id' => $request->getId()]));
            $notification->setMessageUz(__($message->getMessageUz(), ['id' => $request->getId()]));
            $notification->setMessageRu(__($message->getMessageRu(), ['id' => $request->getId()]));
            $notificationRepository->save($notification);

        });

        return $request->getId();

    }
}
