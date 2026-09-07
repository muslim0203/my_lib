<?php

namespace App\Core\Services\Notification;

use App\Core\Repository\Author\AuthorRepository;
use App\Core\Repository\Notification\NotificationRepository;
use App\Core\Services\Notification\Interfaces\NotificationInterface;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;

class NotificationService implements NotificationInterface
{
    public function __construct(
        protected NotificationRepository $notificationRepository,
        protected AuthorRepository       $authorRepository
    )
    {
    }

    public function authorRequests()
    {
        if (!Auth::check()) {
            abort(401, __('Unauthorized user'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $author = $this->authorRepository->findByUserId($user->getId());

        if (empty($author)) {
            return false;
        }

        $notifications = $this->notificationRepository->findAllByAuthor($author->getId());

        // TODO user qismi uchun notification qismini shakllantirish ishlari davom etyabdi
    }
}
