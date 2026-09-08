<?php

namespace App\Http\Controllers\Web\Company;

use App\Core\Filters\Company\CompanyFileSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Company\CompanyFileRepository;
use App\Core\Repository\Company\CompanyRepository;
use App\Core\Services\FileManager\Interface\FileManagerInterface;
use App\Http\Requests\Company\CompanyFileFilterRequest;
use App\Http\Requests\Company\CompanyFileRequest;
use App\Models\Company\CompanyFile;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CompanyFileController extends Controller
{
    private CompanyFileRepository $companyFileRepository;
    private CompanyRepository $companyRepository;
    private FileManagerInterface $fileManagerService;
    public function __construct(
        CompanyFileRepository $companyFileRepository,
        CompanyRepository $companyRepository,
        FileManagerInterface $fileManagerService
    )
    {
        $this->companyFileRepository = $companyFileRepository;
        $this->companyRepository = $companyRepository;
        $this->fileManagerService = $fileManagerService;
    }

    public function create(): Application|View|Factory
    {
        return view('pages.company-file.form',[
            'model' => new CompanyFile(),
            'companies' => $this->companyRepository->findAll(),
            'companyFile' => $this->companyFileRepository->findAll()
        ]);
    }

    /**
     * @param CompanyFileRequest $companyFileRequest
     * @return RedirectResponse
     */
    public function store(CompanyFileRequest $companyFileRequest): RedirectResponse
    {
        $model = new CompanyFile();
        $model->fill($companyFileRequest->validated());
        if(!empty($companyFileRequest->file('file'))){
            $file = $this->fileManagerService->image($companyFileRequest->file('file'));
            $model->setFileId($file->id);
            $model->setFileName($file->original_name);
        }
        try {
            $this->companyFileRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('company-file.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): Application|View|Factory
    {
        $model = $this->findModel($id);

        return view('pages.company-file.form', compact('model'),[
            'companyFile' => $this->companyFileRepository->findAll(),
            'companies' => $this->companyRepository->findAll()
        ]);
    }

    /**
     * @param CompanyFileRequest $companyFileRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(CompanyFileRequest $companyFileRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($companyFileRequest->validated());
        if(!empty($companyFileRequest->file('file'))){
            $file = $this->fileManagerService->image($companyFileRequest->file('file'));
            $model->setFileId($file->id);
            $model->setFileName($file->original_name);
        }
        try {
            $this->companyFileRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('company-file.filter');
    }

    /**
     * @param CompanyFileFilterRequest $companyFileFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(CompanyFileFilterRequest $companyFileFilterRequest): View|Factory|Application
    {
        return view('pages.company-file.filter', [
            'data' => CompanyFileSearchFilter::search($companyFileFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return CompanyFile|Builder
     */
    public function findModel(int $id): CompanyFile|Builder
    {

        return $this->companyFileRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        // Delete.js o'chirishni AJAX DELETE bilan yuboradi. Redirect
        // qaytarilsa, XHR 302 ni AYNI DELETE metodi bilan kuzatadi va
        // filter marshrutida 405 oladi - yozuv o'chirilgan bo'lsa ham
        // foydalanuvchiga xato ko'rinadi. Shuning uchun AJAX uchun JSON.
        if (request()->expectsJson()) {
            return Success::send('Successful removed');
        }

        return redirect()->route('company-file.filter');
    }
}
