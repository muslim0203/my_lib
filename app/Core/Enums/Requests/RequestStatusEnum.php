<?php

namespace App\Core\Enums\Requests;

enum RequestStatusEnum: string
{
    case _CHECKING = 'checking';
    case _APPROVED = 'accepted';
    case _REJECTED = 'rejected';

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
            [
                'id' => self::_CHECKING->value,
                'title' => __('client.Checking'),
            ],
            [
                'id' => self::_APPROVED->value,
                'title' => __('client.Approved'),
            ],
            [
                'id' => self::_REJECTED->value,
                'title' => __('client.Rejected'),
            ]
        ];
    }
}
