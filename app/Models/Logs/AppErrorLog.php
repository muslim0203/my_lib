<?php

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $message
 * @property string|null $context
 * @property string|null $extra
 * @property string|null $level
 * @property string|null $level_name
 * @property string|null $channel
 * @property string|null $ip_address
 * @property string|null $user_agent
 */
class AppErrorLog extends Model
{
    protected $fillable = [
        'message',
        'context',
        'level',
        'level_name',
        'channel',
        'ip_address',
        'user_agent',
        'user_id',
        'extra'
    ];

    public function getExtra(): ?string
    {
        return $this->extra;
    }

    public function setExtra(?string $extra): void
    {
        $this->extra = $extra;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): void
    {
        $this->context = $context;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): void
    {
        $this->level = $level;
    }

    public function getLevelName(): ?string
    {
        return $this->level_name;
    }

    public function setLevelName(?string $level_name): void
    {
        $this->level_name = $level_name;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $ip_address): void
    {
        $this->ip_address = $ip_address;
    }

    public function getUserAgent(): ?string
    {
        return $this->user_agent;
    }

    public function setUserAgent(?string $user_agent): void
    {
        $this->user_agent = $user_agent;
    }
}
