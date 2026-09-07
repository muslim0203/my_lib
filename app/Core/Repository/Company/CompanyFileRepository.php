<?php

namespace App\Core\Repository\Company;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Company\CompanyFile;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CompanyFileRepository
{
    public function get(int $id): Builder|CompanyFile
    {
        return CompanyFile::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param CompanyFile $companyFile
     * @return void
     */
    public function save(CompanyFile $companyFile): void
    {
        if (!$companyFile->save()) {
            throw new \RuntimeException(__('client.Company file save error'));
        }
    }

    public function findAll(): Collection|array
    {
        return CompanyFile::query()
            ->from('company_files as cf')
            ->select([
                'cf.id',
                'cf.' . LanguageHelper::getTitle() . ' AS title',
                'cf.file_id',
            ])
            ->addSelect(DB::raw("CONCAT(f.path,'/',f.file_name) as path"))
            ->leftJoin('files as f','f.id', '=', 'cf.file_id')
            ->where(['cf.enabled' => true])
            ->get();
    }

}
