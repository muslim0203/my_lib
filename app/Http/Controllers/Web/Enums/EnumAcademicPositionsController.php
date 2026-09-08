<?php

namespace App\Http\Controllers\Web\Enums;

use App\Core\Filters\Enums\EnumAcademicPositionSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\AcademicPositionRepository;
use App\Http\Requests\Enums\EnumAcademicPositionFilterRequest;
use App\Http\Requests\Enums\EnumAcademicPositionRequest;
use App\Models\Enums\EnumAcademicPosition;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class EnumAcademicPositionsController extends Controller
{
    private AcademicPositionRepository $enumAcademicPositionRepository;

    public function __construct(AcademicPositionRepository $enumAcademicPositionRepository)
    {
        $this->enumAcademicPositionRepository = $enumAcademicPositionRepository;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumAcademicPosition.form', [
            'model' => new EnumAcademicPosition(),
        ]);
    }

    /**
     * @param EnumAcademicPositionRequest $enumAcademicPositionRequest
     * @return RedirectResponse
     */
    public function store(EnumAcademicPositionRequest $enumAcademicPositionRequest): RedirectResponse
    {
        $model = new EnumAcademicPosition();
        $model->fill($enumAcademicPositionRequest->validated());

        try {
            $this->enumAcademicPositionRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('enum-academic-position.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $model = $this->findModel($id);

        return view('pages.enums.enumAcademicPosition.form', compact('model'), [
            'degrees' => $this->enumAcademicPositionRepository->findAll()
        ]);
    }

    /**
     * @param EnumAcademicPositionRequest $enumAcademicPositionRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(EnumAcademicPositionRequest $enumAcademicPositionRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($enumAcademicPositionRequest->validated());
        try {
            $this->enumAcademicPositionRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('enum-academic-position.filter');
    }

    /**
     * @param EnumAcademicPositionFilterRequest $enumAcademicPositionFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(EnumAcademicPositionFilterRequest $enumAcademicPositionFilterRequest): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumAcademicPosition.filter', [
            'data' => EnumAcademicPositionSearchFilter::search($enumAcademicPositionFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return EnumAcademicPosition|Builder
     */
    public function findModel(int $id): EnumAcademicPosition|Builder
    {

        return $this->enumAcademicPositionRepository->get($id);
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

        return redirect()->route('enum-academic-position.filter');
    }
}
