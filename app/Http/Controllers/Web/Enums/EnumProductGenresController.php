<?php

namespace App\Http\Controllers\Web\Enums;

use App\Core\Filters\Enums\EnumProductGenreSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\ProductGenresRepository;
use App\Http\Requests\Enums\EnumProductGenreFilterRequest;
use App\Http\Requests\Enums\EnumProductGenreRequest;
use App\Models\Enums\EnumProductGenre;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class EnumProductGenresController extends Controller
{
    private ProductGenresRepository $enumProductGenresRepository;

    public function __construct(ProductGenresRepository $enumProductGenresRepository)
    {
        $this->enumProductGenresRepository = $enumProductGenresRepository;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumProductGenre.form', [
            'model' => new EnumProductGenre(),
        ]);
    }

    /**
     * @param EnumProductGenreRequest $enumProductGenreRequest
     * @return RedirectResponse
     */
    public function store(EnumProductGenreRequest $enumProductGenreRequest): RedirectResponse
    {
        $model = new EnumProductGenre();
        $model->fill($enumProductGenreRequest->validated());

        try {
            $this->enumProductGenresRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('enum-product-genre.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $model = $this->findModel($id);

        return view('pages.enums.enumProductGenre.form', compact('model'), [
            'languages' => $this->enumProductGenresRepository->findAll()
        ]);
    }

    /**
     * @param EnumProductGenreRequest $enumProductGenreRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(EnumProductGenreRequest $enumProductGenreRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($enumProductGenreRequest->validated());
        try {
            $this->enumProductGenresRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('enum-product-genre.filter');
    }

    /**
     * @param EnumProductGenreFilterRequest $enumProductGenreFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(EnumProductGenreFilterRequest $enumProductGenreFilterRequest): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumProductGenre.filter', [
            'data' => EnumProductGenreSearchFilter::search($enumProductGenreFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return EnumProductGenre|Builder
     */
    public function findModel(int $id): EnumProductGenre|Builder
    {

        return $this->enumProductGenresRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        return redirect()->route('enum-product-genre.filter');
    }
}
