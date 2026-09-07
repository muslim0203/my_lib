<?php

namespace App\Http\Controllers\Web\Company;

use App\Core\Filters\Company\CompanySearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Company\CompanyRepository;
use App\Http\Requests\Company\CompanyFilterRequest;
use App\Http\Requests\Company\CompanyRequest;
use App\Models\Company\Company;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CompanyController extends Controller
{
    private CompanyRepository $companyRepository;
    public function __construct(
        CompanyRepository $companyRepository
    )
    {
        $this->companyRepository = $companyRepository;
    }

    public function create(): Application|View|Factory
    {
        return view('pages.company.form',[
            'model' => new Company(),
            'company' => $this->companyRepository->findAll()
        ]);
    }

    /**
     * @param CompanyRequest $companyRequest
     * @return RedirectResponse
     */
    public function store(CompanyRequest $companyRequest): RedirectResponse
    {
        $model = new Company();
        $model->fill($companyRequest->validated());

        try {
            $this->companyRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('company.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): Application|View|Factory
    {
        $model = $this->findModel($id);

        return view('pages.company.form', compact('model'),[
            'company' => $this->companyRepository->findAll()
        ]);
    }

    /**
     * @param CompanyRequest $companyRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(CompanyRequest $companyRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($companyRequest->validated());
        try {
            $this->companyRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('company.filter');
    }

    /**
     * @param CompanyFilterRequest $companyFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(CompanyFilterRequest $companyFilterRequest): View|Factory|Application
    {
        return view('pages.company.filter', [
            'data' => CompanySearchFilter::search($companyFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return Company|Builder
     */
    public function findModel(int $id): Company|Builder
    {

        return $this->companyRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        return redirect()->route('company.filter');
    }

}
