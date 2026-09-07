<?php

namespace App\Core\Services\Register;

use App\Core\Enums\Requests\RequestStatusEnum;
use App\Core\Enums\Requests\RequestTypeEnum;
use App\Core\Enums\Steps\ProcessStepEnum;
use App\Core\Filters\Author\AuthorCommentSearchFilter;
use App\Core\Helpers\Transaction;
use App\Core\Repository\Author\AuthorCommentRepository;
use App\Core\Repository\Author\AuthorProfileFileRepository;
use App\Core\Repository\Author\AuthorRepository;
use App\Core\Repository\Notification\NotificationMessageRepository;
use App\Core\Repository\Notification\NotificationRepository;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Repository\Step\ProcessStepRepository;
use App\Core\Repository\User\UserRepository;
use App\Core\Services\Author\AuthorProfileService;
use App\Core\Services\Register\Contracts\Register;
use App\Http\Requests\Authors\AuthorShowRequest;
use App\Http\Resources\Authors\AuthorCommentListResource;
use App\Models\Authors\Author;
use App\Models\Authors\AuthorComments;
use App\Models\Notifications\Notification;
use App\Models\Request\Request;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthorRegisterService implements Register
{
    public function __construct(
        protected AuthorRepository            $authorRepository,
        protected AuthorCommentRepository     $authorCommentRepository,
        protected AuthorProfileFileRepository $authorProfileFileRepository,
        protected AuthorProfileService        $authorProfileService,
        protected ProcessStepRepository       $processStepRepository
    )
    {
    }

    /**
     * @param FormRequest $formRequest
     * @param int|null $id
     * @return int
     */
    public function register(FormRequest $formRequest, ?int $id = null): int
    {
        if (!Auth::check()) {
            abort(400, __('client.Authorization error'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        if ($user->isEmptySocialUser()) {
            abort(400, __('client.User is not defined'));
        }

        $requestRepository = new RequestRepository();

        $request = $requestRepository->findByAuthor($user->getId(), [
            RequestTypeEnum::_AUTHOR->value,
            RequestTypeEnum::_AUTHORITY->value
        ]);

        if (
            !empty($request)
            && in_array($request->getStatus(), [
                RequestStatusEnum::_APPROVED->value,
                RequestStatusEnum::_CHECKING->value
            ])
        ) {
            abort(400, __('client.This action is not permit.'));
        }

        $request = is_null($id) ? new Request() : $requestRepository->getOwned($id, $user->getId());

        if (!empty($id) && !$request->isIsEditable()) {
            throw new BadRequestHttpException(__('client.Request doesnt editable'));
        }

        $request->setAuthorId($user->getId());
        $request->setStatus(RequestStatusEnum::_CHECKING->value);
        $request->setModel(Author::class);
        $request->setRequestTypeId(RequestTypeEnum::_AUTHOR->value);
        $processStep = $this->processStepRepository->getByCodeName(ProcessStepEnum::_PROCESS_ADD_NEW_AUTHOR->value);
        $request->setStepId($processStep->getId());
        $request->setData(json_encode($formRequest->validated()));
        $request->setIsAgree((bool)$formRequest->post('is_agree'));

        /**
         * @var Transaction $transaction
         */
        $transaction = app(Transaction::class);

        $transaction->wrap(function () use ($request, $requestRepository, $user) {

            $requestRepository->save($request);

            /**
             * @var NotificationMessageRepository $notificationMessage
             */
            $notificationMessage = app(NotificationMessageRepository::class);
            $message = $notificationMessage->getByTypeId(1);

            $notification = new Notification();
            $notification->setModel(Author::class);
            $notification->setNotificationMessageId($message->getId());
            $notification->setNotificationTypeId($message->getNotificationTypeId());
            $notification->setUserId($user->getId());
            $notification->setMessageOz(__($message->getMessageOz(), ['id' => $request->getId()]));
            $notification->setMessageUz(__($message->getMessageUz(), ['id' => $request->getId()]));
            $notification->setMessageRu(__($message->getMessageRu(), ['id' => $request->getId()]));
            $notification->setApplyLink(route('author.view', ['id' => $request->getId()]));
            /**
             * @var NotificationRepository $notificationRepository
             */
            $notificationRepository = app(NotificationRepository::class);
            $notificationRepository->save($notification);
        });

        return $request->getId();
    }

    /**
     * @param AuthorShowRequest $authorShowRequest
     * @return User|Builder
     */
    public function showProfile(AuthorShowRequest $authorShowRequest): User|Builder
    {
        $authorShowRequest->validated();

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = app(UserRepository::class);

        return $userRepository->get($authorShowRequest->post('author_id'));

    }

    public function commentList(FormRequest $formRequest, int $id): AnonymousResourceCollection
    {
        return AuthorCommentListResource::collection(AuthorCommentSearchFilter::search($formRequest, $id));
    }

    public function comment(FormRequest $formRequest, int $id): bool
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = app(UserRepository::class);

        $author = $userRepository->get($id);

        $parentId = $formRequest->post('parent_id');

        if (!is_null($parentId)) {
            $parentId = (int)$parentId;
        }

        $commentProduct = new AuthorComments();
        $commentProduct->setAuthorId($author->getId());
        $commentProduct->setComment($formRequest->post('comment'));
        $commentProduct->setUserId($user->getId());
        $commentProduct->setParentId($parentId);
        $this->authorCommentRepository->save($commentProduct);

        return true;
    }
}
