<?php

namespace App\Core\Enums\Products;

enum ProductCategoryEnum: string
{
    case _RECOMMEND_FOR_YOU = 'recommend_for_you';
    case _NEW = 'new';
    case _DISCOUNT = 'discount';
    case _FREE = 'free';
    case _SCIENTIFIC_ARTICLES = 'scientific_articles';
    case _MAGAZINES = 'magazines';
    case _TOP = 'top';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
