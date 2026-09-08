<?php

namespace App\Http\Controllers\Web\Company;

use App\Core\Filters\Company\CompanySocialNetworkSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Company\CompanyRepository;
use App\Core\Repository\Company\CompanySocialNetworkRepository;
use App\Core\Services\FileManager\Interface\FileManagerInterface;
use App\Http\Requests\Company\CompanySocialNetworkFilterRequest;
use App\Http\Requests\Company\CompanySocialNetworkRequest;
use App\Models\Company\CompanySocialNetwork;
use App\Models\Files\File;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CompanySocialNetworkController extends Controller
{
    private CompanySocialNetworkRepository $socialNetworkRepository;
    private CompanyRepository $companyRepository;
    private FileManagerInterface $fileManagerService;

    public function __construct(
        CompanySocialNetworkRepository $socialNetworkRepository,
        CompanyRepository $companyRepository,
        FileManagerInterface $fileManagerService
    )
    {
        $this->socialNetworkRepository = $socialNetworkRepository;
        $this->companyRepository = $companyRepository;
        $this->fileManagerService = $fileManagerService;
    }

    public function create(): Application|View|Factory
    {
        return view('pages.company-social-network.form',[
            'model' => new CompanySocialNetwork(),
            'companyPartner' => $this->socialNetworkRepository->findAll(),
            'companies' => $this->companyRepository->findAll()
        ]);
    }

    /**
     * @param CompanySocialNetworkRequest $companySocialNetworkRequest
     * @return RedirectResponse
     */
    public function store(CompanySocialNetworkRequest $companySocialNetworkRequest): RedirectResponse
    {
        $model = new CompanySocialNetwork();
        $model->fill($companySocialNetworkRequest->validated());
        if(!empty($companySocialNetworkRequest->file('file'))){
            $file = $this->fileManagerService->image($companySocialNetworkRequest->file('file'));
            $model->setLogoId($file->id);
        }
        try {
            $this->socialNetworkRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('company-social-network.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): Application|View|Factory
    {
        $model = $this->findModel($id);

        return view('pages.company-social-network.form', compact('model'),[
            'companySocialNetwork' => $this->socialNetworkRepository->findAll(),
            'companies' => $this->companyRepository->findAll()
        ]);
    }

    /**
     * @param CompanySocialNetworkRequest $companySocialNetworkRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(CompanySocialNetworkRequest $companySocialNetworkRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($companySocialNetworkRequest->validated());
        if(!empty($companySocialNetworkRequest->file('file'))){
            $file = $this->fileManagerService->image($companySocialNetworkRequest->file('file'));
            $model->setLogoId($file->id);
        }
        try {
            $this->socialNetworkRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('company-social-network.filter');
    }

    /**
     * @param CompanySocialNetworkFilterRequest $companySocialNetworkFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(CompanySocialNetworkFilterRequest $companySocialNetworkFilterRequest): View|Factory|Application
    {
        return view('pages.company-social-network.filter', [
            'data' => CompanySocialNetworkSearchFilter::search($companySocialNetworkFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return CompanySocialNetwork|Builder
     */
    public function findModel(int $id): CompanySocialNetwork|Builder
    {

        return $this->socialNetworkRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error');
        }

        Session::flash('success', __('client.Successful removed'));

        // Delete.js o'chirishni AJAX DELETE bilan yuboradi. Redirect
        // qaytarilsa, XHR 302 ni AYNI DELETE metodi bilan kuzatadi va
        // filter marshrutida 405 oladi - yozuv o'chirilgan bo'lsa ham
        // foydalanuvchiga xato ko'rinadi. Shuning uchun AJAX uchun JSON.
        if (request()->expectsJson()) {
            return Success::send('Successful removed');
        }

        return redirect()->route('company-social-network.filter');
    }

}
