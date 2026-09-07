<?php

namespace App\Core\Repository\Enum;

use App\Models\Enums\EnumLanguage;
use Illuminate\Database\Eloquent\Builder;

class LanguagesRepository
{
    /**
     * @param int $id
     * @return Builder|EnumLanguage
     */
    public function get(int $id): Builder|EnumLanguage
    {
        return EnumLanguage::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param EnumLanguage $enumLanguage
     * @return void
     */
    public function save(EnumLanguage $enumLanguage): void
    {
        if (!$enumLanguage->save()) {
            throw new \RuntimeException(__('client.Enum Language save error'));
        }
    }

    public function findAll()
    {
        return EnumLanguage::query()
            ->selectRaw('enum_languages.*')
            ->where(['enabled' => true])
            ->get();
    }
}
