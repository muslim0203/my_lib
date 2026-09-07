<?php

namespace App\Http\Controllers\Web\Enums;

use App\Core\Filters\Enums\EnumProductStatusSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\ProductStatusRepository;
use App\Http\Requests\Enums\EnumProductStatusFilterRequest;
use App\Http\Requests\Enums\EnumProductStatusRequest;
use App\Models\Enums\EnumProductStatus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class EnumProductStatusController extends Controller
{
    private ProductStatusRepository $enumProductStatusRepository;

    public function __construct(ProductStatusRepository $enumProductStatusRepository)
    {
        $this->enumProductStatusRepository = $enumProductStatusRepository;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumProductStatus.form', [
            'model' => new EnumProductStatus(),
        ]);
    }

    /**
     * @param EnumProductStatusRequest $enumProductStatusRequest
     * @return RedirectResponse
     */
    public function store(EnumProductStatusRequest $enumProductStatusRequest): RedirectResponse
    {
        $model = new EnumProductStatus();
        $model->fill($enumProductStatusRequest->validated());

        try {
            $this->enumProductStatusRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('enum-product-status.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $model = $this->findModel($id);

        return view('pages.enums.enumProductStatus.form', compact('model'), [
            'languages' => $this->enumProductStatusRepository->findAll()
        ]);
    }

    /**
     * @param EnumProductStatusRequest $enumProductStatusRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(EnumProductStatusRequest $enumProductStatusRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($enumProductStatusRequest->validated());
        try {
            $this->enumProductStatusRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('enum-product-genre.filter');
    }

    /**
     * @param EnumProductStatusFilterRequest $enumProductStatusFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(EnumProductStatusFilterRequest $enumProductStatusFilterRequest): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumProductStatus.filter', [
            'data' => EnumProductStatusSearchFilter::search($enumProductStatusFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return EnumProductStatus|Builder
     */
    public function findModel(int $id): EnumProductStatus|Builder
    {

        return $this->enumProductStatusRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        return redirect()->route('enum-product-status.filter');
    }
}
