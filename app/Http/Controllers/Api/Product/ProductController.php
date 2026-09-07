<?php

namespace App\Http\Controllers\Api\Product;

use App\Core\Filters\Product\ProductListSearchFilter;
use App\Core\Filters\Product\PersonalProductSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Product\ProductRepository;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Services\Product\ProductService;
use App\Http\Requests\Authors\AuthorProductRequest;
use App\Http\Requests\Products\AssessmentRequest;
use App\Http\Requests\Products\CommentRequest;
use App\Http\Requests\Products\FavoriteProductsFormRequest;
use App\Http\Requests\Products\ProductFilterSearchRequest;
use App\Http\Resources\Products\PersonalProductListResource;
use App\Http\Resources\Products\ProductListResource;
use App\Http\Resources\Products\ProductPersonalViewResource;
use App\Http\Resources\Products\ProductsOrderListResource;
use App\Http\Resources\Products\ProductViewResource;
use App\Http\Resources\Requests\RequestViewResource;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    )
    {
    }

    /**
     * @param ProductFilterSearchRequest $productFilterSearchRequest
     * @return AnonymousResourceCollection
     */
    public function list(ProductFilterSearchRequest $productFilterSearchRequest): AnonymousResourceCollection
    {
        return ProductListResource::collection(ProductListSearchFilter::search($productFilterSearchRequest));
    }

    /**
     * @param ProductRepository $productRepository
     * @param int $id
     * @return JsonResponse
     */
    public function view(ProductRepository $productRepository, int $id): JsonResponse
    {
        return Success::send('Product detail view', new ProductViewResource($productRepository->getById($id)));
    }

    /**
     * @param FormRequest $formRequest
     * @return AnonymousResourceCollection
     */
    public function personalList(FormRequest $formRequest): AnonymousResourceCollection
    {
        return PersonalProductListResource::collection(PersonalProductSearchFilter::search($formRequest));
    }

    /**
     * @param ProductRepository $productRepository
     * @param int $id
     * @return ProductPersonalViewResource
     */
    public function personalView(ProductRepository $productRepository, int $id): ProductPersonalViewResource
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        return new ProductPersonalViewResource($productRepository->findByIdAndUser($id, $user->getId()));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var ProductRepository $productRepository
         */
        $productRepository = app(ProductRepository::class);
        $product = $productRepository->getById($id);

        if ($product->getAuthorId() !== $user->getId()) {
            abort(403, __('client.Forbidden'));
        }

        $product->setIsDeleted(true);
        $productRepository->save($product);

        return Success::send('Product deleted successfully');
    }

    /**
     * @param RequestRepository $requestRepository
     * @param int $id
     * @return JsonResponse
     */
    public function viewRequest(RequestRepository $requestRepository, int $id): JsonResponse
    {
        return Success::send('Product request view', new RequestViewResource($requestRepository->get($id)));
    }

    /**
     * @param AssessmentRequest $assessmentRequest
     * @param int $id
     * @return JsonResponse
     */
    public function assessment(AssessmentRequest $assessmentRequest, int $id): JsonResponse
    {
        $this->productService->assessment($assessmentRequest, $id);

        return Success::send('Successfully assessment of product');
    }

    /**
     * @param CommentRequest $commentRequest
     * @param int $id
     * @return JsonResponse
     */
    public function comment(CommentRequest $commentRequest, int $id): JsonResponse
    {
        $this->productService->comment($commentRequest, $id);

        return Success::send('Successfully comment of product');
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function assessmentList(int $id): JsonResponse
    {
        return Success::send('Product assessment list', $this->productService->assessmentList($id));
    }

    /**
     * @param FormRequest $formRequest
     * @param int $id
     * @return AnonymousResourceCollection
     */
    public function commentList(FormRequest $formRequest, int $id): AnonymousResourceCollection
    {
        return $this->productService->commentList($formRequest, $id);
    }

    public function authorProductList(AuthorProductRequest $authorProductRequest): JsonResponse
    {
        return Success::send('Author Product List', $this->productService->authorProductList($authorProductRequest));
    }

    /**
     * @param int $productId
     * @return JsonResponse
     */
    public function similarProduct(int $productId): JsonResponse
    {
        return Success::send('Author Product List', ProductListResource::collection($this->productService->similarProducts($productId)));
    }

    /**
     * @param FavoriteProductsFormRequest $favoriteProductsFormRequest
     * @return JsonResponse
     */
    public function favoriteList(FavoriteProductsFormRequest $favoriteProductsFormRequest): JsonResponse
    {
        return Success::send('Author Product List', ProductListResource::collection($this->productService->favoriteProducts($favoriteProductsFormRequest)));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function buy(int $id): JsonResponse
    {
        $this->productService->buy($id);
        return Success::send('Successful done buy');
    }

    /**
     * @return JsonResponse
     */
    public function myList(): JsonResponse
    {
        return Success::send('Successful done buy', ProductsOrderListResource::collection($this->productService->myList()));
    }

    /**
     * @param int $id
     * @return Response
     */
    public function setViewCount(int $id): Response
    {
        /**
         * @var ProductRepository $productRepository
         */
        $productRepository = app(ProductRepository::class);
        $product = $productRepository->getById($id);
        $viewCount = ($product->getViewsCount() ?? 0) + 1;
        $product->setViewsCount($viewCount);
        $productRepository->save($product);

        return response()->noContent();
    }
}
