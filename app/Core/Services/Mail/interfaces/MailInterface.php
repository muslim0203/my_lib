<?php

namespace App\Core\Services\Mail\interfaces;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Mail\SentMessage;

interface MailInterface
{
    public function send(string $to, ?Mailable $mailable = null);

    public function getCode(): int;

    public function getHashCode(): string;

    public function getSentMessage(): SentMessage|null;
}
