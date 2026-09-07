<?php

namespace App\Http\Controllers\Web\Report;

use App\Core\Repository\Enum\CategoriesRepository;
use App\Core\Repository\Enum\ProductTagRepository;
use App\Core\Repository\Product\ProductGenreRepository;
use App\Core\Search\ProductOrderSearch;
use App\Core\Search\UserRegisterSearch;
use App\Http\Requests\Report\ProductOrderSearchRequest;
use App\Http\Requests\Report\UserRegisterSearchRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    private CategoriesRepository $categoriesRepository;
    private ProductGenreRepository $genreRepository;
    private ProductTagRepository $tagRepository;

    public function __construct(
        CategoriesRepository $categoriesRepository,
        ProductGenreRepository $genreRepository,
        ProductTagRepository $tagRepository
    )
    {
        $this->categoriesRepository = $categoriesRepository;
        $this->genreRepository = $genreRepository;
        $this->tagRepository = $tagRepository;
    }

    public function userRegisterSearch(UserRegisterSearchRequest $registerSearchRequest): Application|View|Factory
    {
        return view('pages.report.user-register-search', [
            'data' => UserRegisterSearch::search($registerSearchRequest)
        ]);
    }

    public function productOrderSearch(ProductOrderSearchRequest $productOrderSearchRequest): Application|View|Factory
    {
        return view('pages.report.product-order-search', [
            'data' => ProductOrderSearch::search($productOrderSearchRequest),
            'categories' => $this->categoriesRepository->findAll(),
            'genres' => $this->genreRepository->findAll(),
            'tags' => $this->tagRepository->findAll()
        ]);
    }

    public function topBuyers(ProductOrderSearchRequest $productOrderSearchRequest): Factory|Application|View
    {
        return view('pages.report.top-buyers', [
            'data' => ProductOrderSearch::searchTopBuyers($productOrderSearchRequest)
        ]);
    }

    public function topProducts(ProductOrderSearchRequest $productOrderSearchRequest): Factory|Application|View
    {
        return view('pages.report.top-products', [
            'data' => ProductOrderSearch::searchTopProducts($productOrderSearchRequest),
            'categories' => $this->categoriesRepository->findAll(),
            'genres' => $this->genreRepository->findAll(),
            'tags' => $this->tagRepository->findAll()
        ]);
    }
}
