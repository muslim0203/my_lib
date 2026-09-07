<?php

namespace App\Core\Repository\Proverb;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Proverbs\Proverb;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProverbRepository
{
    public function getById(int $id): Proverb|Builder
    {
        return Proverb::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function save(Proverb $proverb): void
    {
        if (!$proverb->save()) {
            throw new \RuntimeException(__('client.Proverb save error'));
        }
    }

    public function findRandom()
    {
        return Proverb::orderByRaw('RANDOM()')
            ->select(['id',
                LanguageHelper::getAuthor() . ' AS author',
                LanguageHelper::getContent() . ' AS content'])
            ->first();
    }
}
