<?php

namespace App\Http\Controllers\Web\MainBanner;

use App\Core\Filters\Company\CompanySearchFilter;
use App\Core\Filters\MainBanner\MainBannerSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Company\CompanyRepository;
use App\Core\Repository\MainBanner\MainBannerRepository;
use App\Core\Services\MainBanner\MainBannerService;
use App\Http\Requests\Company\CompanyFilterRequest;
use App\Http\Requests\Company\CompanyRequest;
use App\Http\Requests\MainBanner\MainBannerFilterRequest;
use App\Http\Requests\MainBanner\MainBannerRequest;
use App\Models\Company\Company;
use App\Models\MainBanner\MainBanner;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MainBannerController extends Controller
{
    private MainBannerRepository $mainBannerRepository;
    private MainBannerService $mainBannerService;
    public function __construct(
        MainBannerRepository $mainBannerRepository,
        MainBannerService $mainBannerService
    )
    {
        $this->mainBannerRepository = $mainBannerRepository;
        $this->mainBannerService = $mainBannerService;
    }

    public function create(): Application|View|Factory
    {
        return view('pages.banner.form',[
            'model' => new MainBanner(),
            'banner' => $this->mainBannerRepository->findAll()
        ]);
    }

    /**
     * @param MainBannerRequest $mainBannerRequest
     * @return RedirectResponse
     */
    public function store(MainBannerRequest $mainBannerRequest): RedirectResponse
    {
        $model = new MainBanner();
        $model->fill($mainBannerRequest->validated());
        $files = $mainBannerRequest->file('banner_files');
        try {
            $this->mainBannerService->create($model,$files);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('banner.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): Application|View|Factory
    {
        $model = $this->findModel($id);

        return view('pages.banner.form', compact('model'),[
            'banner' => $this->mainBannerRepository->findAll()
        ]);
    }

    /**
     * @param MainBannerRequest $mainBannerRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(MainBannerRequest $mainBannerRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($mainBannerRequest->validated());
        $files = $mainBannerRequest->file('banner_files');
        try {
            $this->mainBannerService->update($model,$files);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('banner.filter');
    }

    /**
     * @param MainBannerFilterRequest $mainBannerFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(MainBannerFilterRequest $mainBannerFilterRequest): View|Factory|Application
    {
        return view('pages.banner.filter', [
            'data' => MainBannerSearchFilter::search($mainBannerFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return MainBanner|Builder
     */
    public function findModel(int $id): MainBanner|Builder
    {

        return $this->mainBannerRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        return redirect()->route('banner.filter');
    }

}
