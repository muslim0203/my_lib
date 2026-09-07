<?php

namespace App\Core\Services\Mail;

use App\Core\Services\Mail\interfaces\MailInterface;
use App\Mail\VerifyMail;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class MailService implements MailInterface
{
    private ?SentMessage $sentMessage = null;

    public function __construct(
        public readonly int $code,
        protected VerifyMail   $verifyMail
    )
    {
    }

    /**
     * @param string $to
     * @param Mailable|null $mailable
     * @return void
     */
    public function send(string $to, ?Mailable $mailable = null): void
    {
        $mailable = is_null($mailable) ? $this->verifyMail : $mailable;

        $this->sentMessage = Mail::to($to)->send($mailable);
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getHashCode(): string
    {
        return Hash::make((string)$this->code);
    }

    /**
     * @return SentMessage|null
     */
    public function getSentMessage(): SentMessage|null
    {
        return $this->sentMessage;
    }

}
