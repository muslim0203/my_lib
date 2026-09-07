<?php

namespace App\Logging;

use App\Models\Logs\AppErrorLog;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DatabaseHandleLogging extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        /**
         * @var User|null $user
         */
        $user = Auth::user();

        AppErrorLog::query()->insert([
            'level' => $record->level->value,
            'message' => $record->message,
            'context' => json_encode($record->context),
            'extra' => json_encode($record->extra),
            'channel' => $record->channel,
            'level_name' => $record->level->name,
            'user_id' => $user?->getId(),
            'ip_address' => request()->getClientIp(),
            'user_agent' => request()->userAgent(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
