<?php

namespace App\Http\Controllers\Web\Product;

use App\Core\Filters\Product\ProductSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Product\ProductRepository;
use App\Http\Requests\Products\ProductFilterRequest;
use App\Models\Products\Product;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function filter(ProductFilterRequest $productFilterRequest): View|Factory|Application
    {
        return view('pages.product.filter', [
            'data' => ProductSearchFilter::search($productFilterRequest)
        ]);
    }

    public function view(int $id): Factory|View|Application
    {
        $data = $this->productRepository->getView($id);

        return view('pages.product.view', compact('data'));
    }

    public function delete(int $id): JsonResponse|RedirectResponse
    {
        $model = $this->findModel($id);
        $model->setIsDeleted(true);
        $this->productRepository->save($model);

        if (!$model->is_deleted) {
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

        return redirect()->route('product.filter');
    }

    public function findModel(int $id): Product|Builder
    {
        return $this->productRepository->getById($id);
    }
}
