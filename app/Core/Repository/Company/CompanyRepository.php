<?php

namespace App\Core\Repository\Company;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Company\Company;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository
{
    public function get(int $id): Builder|Company
    {
        return Company::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param Company $company
     * @return void
     */
    public function save(Company $company): void
    {
        if (!$company->save()) {
            throw new \RuntimeException(__('client.Company save error'));
        }
    }

    public function findAll(): Collection|array
    {
        return Company::query()
            ->select(['id', LanguageHelper::getTitle() . ' AS title',LanguageHelper::getContent() . ' AS content'])
            ->where(['enabled' => true])
            ->get();
    }
    public function findOne()
    {
        return Company::query()
            ->where(['enabled' => true])
            ->orderBy('updated_at', 'desc')
            ->limit(1)
            ->get();
    }

}
