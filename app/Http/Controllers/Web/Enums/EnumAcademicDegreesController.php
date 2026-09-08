<?php

namespace App\Http\Controllers\Web\Enums;

use App\Core\Filters\Enums\EnumAcademicDegreeSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\AcademicDegreeRepository;
use App\Http\Requests\Enums\EnumAcademicDegreeFilterRequest;
use App\Http\Requests\Enums\EnumAcademicDegreeRequest;
use App\Models\Enums\EnumAcademicDegree;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class EnumAcademicDegreesController extends Controller
{
    private AcademicDegreeRepository $enumAcademicDegreeRepository;

    public function __construct(AcademicDegreeRepository $enumAcademicDegreeRepository)
    {
        $this->enumAcademicDegreeRepository = $enumAcademicDegreeRepository;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumAcademicDegree.form', [
            'model' => new EnumAcademicDegree(),
        ]);
    }

    /**
     * @param EnumAcademicDegreeRequest $enumAcademicDegreeRequest
     * @return RedirectResponse
     */
    public function store(EnumAcademicDegreeRequest $enumAcademicDegreeRequest): RedirectResponse
    {
        $model = new EnumAcademicDegree();
        $model->fill($enumAcademicDegreeRequest->validated());

        try {
            $this->enumAcademicDegreeRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('enum-academic-degree.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $model = $this->findModel($id);

        return view('pages.enums.enumAcademicDegree.form', compact('model'), [
            'degrees' => $this->enumAcademicDegreeRepository->findAll()
        ]);
    }

    /**
     * @param EnumAcademicDegreeRequest $enumAcademicDegreeRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(EnumAcademicDegreeRequest $enumAcademicDegreeRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($enumAcademicDegreeRequest->validated());
        try {
            $this->enumAcademicDegreeRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('enum-academic-degree.filter');
    }

    /**
     * @param EnumAcademicDegreeFilterRequest $enumAcademicDegreeFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(EnumAcademicDegreeFilterRequest $enumAcademicDegreeFilterRequest): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumAcademicDegree.filter', [
            'data' => EnumAcademicDegreeSearchFilter::search($enumAcademicDegreeFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return EnumAcademicDegree|Builder
     */
    public function findModel(int $id): EnumAcademicDegree|Builder
    {

        return $this->enumAcademicDegreeRepository->get($id);
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

        return redirect()->route('enum-academic-degree.filter');
    }
}
