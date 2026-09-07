<?php

namespace App\Logging;


use Monolog\Logger;

class DatabaseLogger
{
    /**
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        return new Logger('database', [new DatabaseHandleLogging()]);
    }
}
