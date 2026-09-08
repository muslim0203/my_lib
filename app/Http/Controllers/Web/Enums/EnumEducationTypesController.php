<?php

namespace App\Http\Controllers\Web\Enums;

use App\Core\Filters\Enums\EnumEducationTypeSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\EducationTypeRepository;
use App\Http\Requests\Enums\EnumEducationTypeFilterRequest;
use App\Http\Requests\Enums\EnumEducationTypeRequest;
use App\Models\Enums\EnumEducationType;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class EnumEducationTypesController extends Controller
{
    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumEducationType.form', [
            'model' => new EnumEducationType()
        ]);
    }

    /**
     * @param EnumEducationTypeRequest $educationTypeRequest
     * @param EducationTypeRepository $educationTypeRepository
     * @return RedirectResponse
     */
    public function store(EnumEducationTypeRequest $educationTypeRequest, EducationTypeRepository $educationTypeRepository): RedirectResponse
    {
        $model = new EnumEducationType();
        $model->fill($educationTypeRequest->validated());

        try {
            $educationTypeRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('enum-education-type.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $model = $this->findModel($id);

        return view('pages.enums.enumEducationType.form', compact('model'));
    }

    /**
     * @param EnumEducationTypeRequest $educationTypeRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(EnumEducationTypeRequest $educationTypeRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($educationTypeRequest->validated());

        /**
         * @var EducationTypeRepository $educationTypeRepository
         */
        $educationTypeRepository = app(EducationTypeRepository::class);
        try {
            $educationTypeRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('enum-education-type.filter');
    }

    /**
     * @param EnumEducationTypeFilterRequest $educationTypeFilterRequest
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function filter(EnumEducationTypeFilterRequest $educationTypeFilterRequest): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumEducationType.filter', [
            'data' => EnumEducationTypeSearchFilter::search($educationTypeFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return EnumEducationType|Builder
     */
    public function findModel(int $id): EnumEducationType|Builder
    {
        /**
         * @var EducationTypeRepository $educationTypeRepository
         */
        $educationTypeRepository = app(EducationTypeRepository::class);
        return $educationTypeRepository->get($id);
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

        return redirect()->route('enum-education-type.filter');
    }
}
