<?php

namespace App\Core\Repository\Company;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Company\CompanyPartner;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CompanyPartnerRepository
{
    public function get(int $id): Builder|CompanyPartner
    {
        return CompanyPartner::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param CompanyPartner $companyPartner
     * @return void
     */
    public function save(CompanyPartner $companyPartner): void
    {
        if (!$companyPartner->save()) {
            throw new \RuntimeException(__('client.Company Partner save error'));
        }
    }

    public function findAll(): Collection|array
    {
        return CompanyPartner::query()
            ->where(['enabled' => true])
            ->get();
    }
}
