<?php

namespace App\Http\Controllers\Web\Enums;

use App\Core\Filters\Enums\EnumLanguageSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\LanguagesRepository;
use App\Http\Requests\Enums\EnumLanguageFilterRequest;
use App\Http\Requests\Enums\EnumLanguageRequest;
use App\Models\Enums\EnumLanguage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class EnumLanguagesController extends Controller
{
    private LanguagesRepository $enumLanguagesRepository;

    public function __construct(LanguagesRepository $enumLanguagesRepository)
    {
        $this->enumLanguagesRepository = $enumLanguagesRepository;
    }
    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumLanguage.form',[
            'model' => new EnumLanguage()
        ]);
    }

    /**
     * @param EnumLanguageRequest $enumLanguageRequest
     * @return RedirectResponse
     */
    public function store(EnumLanguageRequest $enumLanguageRequest): RedirectResponse
    {
        $model = new EnumLanguage();
        $model->fill($enumLanguageRequest->validated());

        try {
            $this->enumLanguagesRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('enum-language.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $model = $this->findModel($id);

        return view('pages.enums.enumLanguage.form', compact('model'),[
            'languages' => $this->enumLanguagesRepository->findAll()
        ]);
    }

    /**
     * @param EnumLanguageRequest $enumLanguageRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(EnumLanguageRequest $enumLanguageRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($enumLanguageRequest->validated());
        try {
            $this->enumLanguagesRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('enum-language.filter');
    }

    /**
     * @param EnumLanguageFilterRequest $enumLanguageFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(EnumLanguageFilterRequest $enumLanguageFilterRequest): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('pages.enums.enumLanguage.filter', [
            'data' => EnumLanguageSearchFilter::search($enumLanguageFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return EnumLanguage|Builder
     */
    public function findModel(int $id): EnumLanguage|Builder
    {

        return $this->enumLanguagesRepository->get($id);
    }

    public function destroy(int $id)
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

        return redirect()->route('enum-language.filter');
    }
}
