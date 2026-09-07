<?php

namespace App\Core\Repository\Company;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Company\CompanySocialNetwork;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CompanySocialNetworkRepository
{
    public function get(int $id): Builder|CompanySocialNetwork
    {
        return CompanySocialNetwork::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param CompanySocialNetwork $companySocialNetwork
     * @return void
     */
    public function save(CompanySocialNetwork $companySocialNetwork): void
    {
        if (!$companySocialNetwork->save()) {
            throw new \RuntimeException(__('client.Company Social save error'));
        }
    }

    public function findAll(): Collection|array
    {
        return CompanySocialNetwork::query()
            ->where(['enabled' => true])
            ->get();
    }
}
