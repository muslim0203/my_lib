<?php

namespace App\Http\Controllers\Web\Enums;

use App\Core\Filters\Enums\EnumProductTypeSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\ProductTypeRepository;
use App\Http\Requests\Enums\EnumProductTypeFilterRequest;
use App\Http\Requests\Enums\EnumProductTypeRequest;
use App\Models\Enums\EnumProductType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class EnumProductTypesController extends Controller
{
    private ProductTypeRepository $enumProductTypeRepository;

    public function __construct(ProductTypeRepository $enumProductTypeRepository)
    {
        $this->enumProductTypeRepository = $enumProductTypeRepository;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumProductType.form', [
            'model' => new EnumProductType(),
        ]);
    }

    /**
     * @param EnumProductTypeRequest $enumProductTypeRequest
     * @return RedirectResponse
     */
    public function store(EnumProductTypeRequest $enumProductTypeRequest): RedirectResponse
    {
        $model = new EnumProductType();
        $model->fill($enumProductTypeRequest->validated());

        try {
            $this->enumProductTypeRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('enum-product-type.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $model = $this->findModel($id);

        return view('pages.enums.enumProductType.form', compact('model'), [
            'types' => $this->enumProductTypeRepository->findAll()
        ]);
    }

    /**
     * @param EnumProductTypeRequest $enumProductTypeRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(EnumProductTypeRequest $enumProductTypeRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($enumProductTypeRequest->validated());
        try {
            $this->enumProductTypeRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('enum-product-type.filter');
    }

    /**
     * @param EnumProductTypeFilterRequest $enumProductTypeFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(EnumProductTypeFilterRequest $enumProductTypeFilterRequest): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumProductType.filter', [
            'data' => EnumProductTypeSearchFilter::search($enumProductTypeFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return EnumProductType|Builder
     */
    public function findModel(int $id): EnumProductType|Builder
    {

        return $this->enumProductTypeRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|RedirectResponse
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

        return redirect()->route('enum-product-type.filter');
    }
}
