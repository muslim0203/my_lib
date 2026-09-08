<?php

namespace App\Http\Controllers\Web\Proverb;

use App\Core\Filters\Proverb\ProverbSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Proverb\ProverbRepository;
use App\Http\Requests\Proverb\ProverbRequest;
use App\Http\Requests\Proverb\ProverbStoreRequest;
use App\Models\Proverbs\Proverb;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class ProverbController extends Controller
{
    private ProverbRepository $proverbRepository;

    public function __construct(ProverbRepository $proverbRepository)
    {
        $this->proverbRepository = $proverbRepository;
    }

    public function filter(ProverbRequest $proverbRequest)
    {
        return view('pages.proverb.filter', [
            'data' => ProverbSearchFilter::search($proverbRequest)
        ]);
    }

    public function create()
    {
        return view('pages.proverb.form')->with('model', new Proverb());
    }

    public function store(ProverbStoreRequest $proverbStoreRequest)
    {
        $model = new Proverb();
        $model->fill($proverbStoreRequest->validated());

        try {
            $this->proverbRepository->save($model);
        } catch (\RuntimeException $exception) {
            return back()->with('exception', $exception->getMessage());
        }
        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('proverb.filter');
    }

    public function edit(int $id)
    {
        $model = $this->findModel($id);
        return view('pages.proverb.form', compact('model'));
    }

    public function update(int $id, ProverbStoreRequest $proverbStoreRequest)
    {
        $model = $this->findModel($id);
        $model->fill($proverbStoreRequest->validated());

        try {
            $this->proverbRepository->save($model);
        } catch (\RuntimeException $exception) {
            return back()->with('exception', $exception->getMessage());
        }
        Session::flash('success', __('client.Update saved'));

        return redirect()->route('proverb.filter');
    }

    public function view(int $id): Factory|View|Application
    {
        $data = $this->proverbRepository->getById($id);

        return view('pages.proverb.view', compact('data'));
    }

    public function delete(int $id)
    {
        $model = $this->findModel($id);
        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        // Boshqa admin kontrollerlari kabi filter sahifasiga qaytariladi.
        // Ilgari metod umuman hech narsa qaytarmasdi (bo'sh 200).
        // Delete.js o'chirishni AJAX DELETE bilan yuboradi. Redirect
        // qaytarilsa, XHR 302 ni AYNI DELETE metodi bilan kuzatadi va
        // filter marshrutida 405 oladi - yozuv o'chirilgan bo'lsa ham
        // foydalanuvchiga xato ko'rinadi. Shuning uchun AJAX uchun JSON.
        if (request()->expectsJson()) {
            return Success::send('Successful removed');
        }

        return redirect()->route('proverb.filter');
    }

    public function findModel(int $id): Proverb
    {
        return $this->proverbRepository->getById($id);
    }
}
