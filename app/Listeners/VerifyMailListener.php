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

        $userVerifyMailToken->setExpireAt(
            now()->addMinutes((int)config('auth.mail_code_expire'))->format('Y-m-d H:i:s')
        );

        $mailService->send($user->getEmail());

        // Ochiq matnli kod bazaga yozilmaydi: faqat uning hash'i.
        // Eski `token` ustuni NOT NULL bo'lgani uchun unga ma'nosiz
        // qiymat yoziladi, sir emas.
        $userVerifyMailToken->setToken('');
        $userVerifyMailToken->setTokenHash($mailService->getHashCode());

        // Yangi kod chiqarilishi eski kodni bekor qiladi va urinishlar
        // hisobini nolga qaytaradi.
        $userVerifyMailToken->setAttempts(0);
        $userVerifyMailToken->setUsedAt(null);
        $userVerifyMailToken->setEnabled(true);

        $this->usersVerifyTokenMailRepository->save($userVerifyMailToken);
    }
}
