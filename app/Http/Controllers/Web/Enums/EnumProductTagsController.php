<?php

namespace App\Http\Controllers\Web\Enums;

use App\Core\Filters\Enums\EnumProductTagSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\ProductTagRepository;
use App\Http\Requests\Enums\EnumProductTagFilterRequest;
use App\Http\Requests\Enums\EnumProductTagRequest;
use App\Models\Enums\EnumProductTag;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class EnumProductTagsController extends Controller
{
    public function __construct(protected ProductTagRepository $productTagRepository)
    {
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumProductTag.form', [
            'model' => new EnumProductTag(),
        ]);
    }

    /**
     * @param EnumProductTagRequest $enumProductTagRequest
     * @return RedirectResponse
     */
    public function store(EnumProductTagRequest $enumProductTagRequest): RedirectResponse
    {
        $model = new EnumProductTag();
        $model->fill($enumProductTagRequest->validated());

        try {
            $this->productTagRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('enum-product-tag.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $model = $this->findModel($id);

        return view('pages.enums.enumProductTag.form', compact('model'), [
            'languages' => $this->productTagRepository->findAll()
        ]);
    }

    /**
     * @param EnumProductTagRequest $enumProductTagRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(EnumProductTagRequest $enumProductTagRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($enumProductTagRequest->validated());
        try {
            $this->productTagRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('enum-product-tag.filter');
    }

    /**
     * @param EnumProductTagFilterRequest $enumProductTagFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(EnumProductTagFilterRequest $enumProductTagFilterRequest): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumProductTag.filter', [
            'data' => EnumProductTagSearchFilter::search($enumProductTagFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return EnumProductTag|Builder
     */
    public function findModel(int $id): EnumProductTag|Builder
    {

        return $this->productTagRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        return redirect()->route('enum-product-tag.filter');
    }
}
