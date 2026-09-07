<?php

namespace App\Http\Controllers\Web\Company;

use App\Core\Filters\Company\CompanyPartnerSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Company\CompanyPartnerRepository;
use App\Core\Repository\Company\CompanyRepository;
use App\Core\Services\FileManager\Interface\FileManagerInterface;
use App\Http\Requests\Company\CompanyPartnerFilterRequest;
use App\Http\Requests\Company\CompanyPartnerRequest;
use App\Models\Company\CompanyPartner;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CompanyPartnerController extends Controller
{
    private CompanyPartnerRepository $companyPartnerRepository;
    private CompanyRepository $companyRepository;
    private FileManagerInterface $fileManagerService;
    public function __construct(
        CompanyPartnerRepository $companyPartnerRepository,
        CompanyRepository $companyRepository,
        FileManagerInterface $fileManagerService
    )
    {
        $this->companyPartnerRepository = $companyPartnerRepository;
        $this->companyRepository = $companyRepository;
        $this->fileManagerService = $fileManagerService;
    }

    public function create(): Application|View|Factory
    {
        return view('pages.company-partner.form',[
            'model' => new CompanyPartner(),
            'companies' => $this->companyRepository->findAll(),
            'companyPartner' => $this->companyPartnerRepository->findAll()
        ]);
    }

    /**
     * @param CompanyPartnerRequest $companyPartnerRequest
     * @return RedirectResponse
     */
    public function store(CompanyPartnerRequest $companyPartnerRequest): RedirectResponse
    {
        $model = new CompanyPartner();
        $model->fill($companyPartnerRequest->validated());
        if(!empty($companyPartnerRequest->file('file'))){
            $file = $this->fileManagerService->image($companyPartnerRequest->file('file'));
            $model->setLogoId($file->id);
        }
        try {
            $this->companyPartnerRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('company-partner.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): Application|View|Factory
    {
        $model = $this->findModel($id);

        return view('pages.company-partner.form', compact('model'),[
            'companyPartner' => $this->companyPartnerRepository->findAll(),
            'companies' => $this->companyRepository->findAll()
        ]);
    }

    /**
     * @param CompanyPartnerRequest $companyPartnerRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(CompanyPartnerRequest $companyPartnerRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($companyPartnerRequest->validated());
        if(!empty($companyPartnerRequest->file('file'))){
            $file = $this->fileManagerService->image($companyPartnerRequest->file('file'));
            $model->setLogoId($file->id);
        }
        try {
            $this->companyPartnerRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('company-partner.filter');
    }

    /**
     * @param CompanyPartnerFilterRequest $companyPartnerFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(CompanyPartnerFilterRequest $companyPartnerFilterRequest): View|Factory|Application
    {
        return view('pages.company-partner.filter', [
            'data' => CompanyPartnerSearchFilter::search($companyPartnerFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return CompanyPartner|Builder
     */
    public function findModel(int $id): CompanyPartner|Builder
    {

        return $this->companyPartnerRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        return redirect()->route('company-partner.filter');
    }

}
