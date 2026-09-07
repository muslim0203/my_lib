<?php

namespace App\Listeners;

use App\Core\Repository\User\UsersVerifyTokenMailRepository;
use App\Core\Services\Mail\interfaces\MailInterface;
use App\Events\VerifyMailEvent;
use App\Models\Users\UsersVerifyMailToken;

class VerifyMailListener
{
    /**
     * @param MailInterface $mailService
     * @param UsersVerifyTokenMailRepository $usersVerifyTokenMailRepository
     */
    public function __construct(
        protected MailInterface                  $mailService,
        protected UsersVerifyTokenMailRepository $usersVerifyTokenMailRepository
    )
    {
        //
    }

    /**
     * @param VerifyMailEvent $event
     * @return void
     */
    public function handle(VerifyMailEvent $event): void
    {
        $user = $event->user;
        $mailService = $this->mailService;

        $userVerifyMailToken = $this->usersVerifyTokenMailRepository->findByUser($user->getId());

        if (empty($userVerifyMailToken)) {
            $userVerifyMailToken = new UsersVerifyMailToken();
            $userVerifyMailToken->setUserId($user->getId());
        }

        $userVerifyMailToken->setExpireAt(now()->addMinutes(config('auth.mail_code_expire'))->toString());

        $mailService->send($user->getEmail());

        $userVerifyMailToken->setToken($mailService->getCode());
        $userVerifyMailToken->setEnabled(true);
        $this->usersVerifyTokenMailRepository->save($userVerifyMailToken);
    }
}
