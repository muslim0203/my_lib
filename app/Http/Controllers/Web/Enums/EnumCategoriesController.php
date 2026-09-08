<?php

namespace App\Http\Controllers\Web\Enums;

use App\Core\Filters\Enums\EnumCategoriesSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\CategoriesRepository;
use App\Http\Requests\Enums\EnumCategoriesFilterRequest;
use App\Http\Requests\Enums\EnumCategoriesRequest;
use App\Models\Enums\EnumCategories;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class EnumCategoriesController extends Controller
{
    private CategoriesRepository $enumCategoriesRepository;

    public function __construct(CategoriesRepository $enumCategoriesRepository)
    {
        $this->enumCategoriesRepository = $enumCategoriesRepository;
    }
    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumCategories.form',[
            'model' => new EnumCategories(),
            'categories' => $this->enumCategoriesRepository->findAll()
        ]);
    }

    /**
     * @param EnumCategoriesRequest $enumCategoriesRequest
     * @return RedirectResponse
     */
    public function store(EnumCategoriesRequest $enumCategoriesRequest): RedirectResponse
    {
        $model = new EnumCategories();
        $model->fill($enumCategoriesRequest->validated());

        try {
            $this->enumCategoriesRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('enum-categories.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $model = $this->findModel($id);

        return view('pages.enums.enumCategories.form', compact('model'),[
            'categories' => $this->enumCategoriesRepository->findAll()
        ]);
    }

    /**
     * @param EnumCategoriesRequest $enumCategoriesRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(EnumCategoriesRequest $enumCategoriesRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($enumCategoriesRequest->validated());
        try {
            $this->enumCategoriesRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('enum-categories.filter');
    }

    /**
     * @param EnumCategoriesFilterRequest $enumCategoriesFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(EnumCategoriesFilterRequest $enumCategoriesFilterRequest): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumCategories.filter', [
            'data' => EnumCategoriesSearchFilter::search($enumCategoriesFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return EnumCategories|Builder
     */
    public function findModel(int $id): EnumCategories|Builder
    {

        return $this->enumCategoriesRepository->get($id);
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

        return redirect()->route('enum-categories.filter');
    }
}
