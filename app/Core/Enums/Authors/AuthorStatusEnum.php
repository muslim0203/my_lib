<?php

namespace App\Core\Enums\Authors;

enum AuthorStatusEnum: int
{
    case _ACTIVE = 1;
    case _UN_CONFIRMED = 2;
    case _DELETED = 0;
    case _CANCELED = 3;

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array
     */
    public static function getListLabel(): array
    {
        return [
            self::_ACTIVE->value       => __('enum.Active'),
            self::_UN_CONFIRMED->value => __('enum.Un Confirmed'),
            self::_DELETED->value      => __('enum.Deleted'),
            self::_CANCELED->value     => __('enum.Canceled')
        ];
    }
}
