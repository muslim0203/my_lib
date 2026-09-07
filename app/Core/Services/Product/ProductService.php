<?php

namespace App\Core\Services\Product;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Enums\Requests\RequestStatusEnum;
use App\Core\Enums\Requests\RequestTypeEnum;
use App\Core\Filters\Product\FavoriteProductSearchFilter;
use App\Core\Filters\Product\ProductCommentSearchFilter;
use App\Core\Filters\Product\ProductsOrderFilter;
use App\Core\Helpers\Transaction;
use App\Core\Repository\LInks\LinkProductGenreRepository;
use App\Core\Repository\LInks\LinkProductTypeRepository;
use App\Core\Repository\LInks\LinkUserProductRepository;
use App\Core\Repository\Notification\NotificationMessageRepository;
use App\Core\Repository\Notification\NotificationRepository;
use App\Core\Repository\Product\ProductAssessmentRepository;
use App\Core\Repository\Product\ProductCommentRepository;
use App\Core\Repository\Product\ProductRepository;
use App\Core\Repository\Product\ProductsOrderRepository;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Services\Product\Contracts\ProductContract;
use App\Http\Requests\Authors\AuthorProductRequest;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Resources\Products\ProductCommentListResource;
use App\Models\Enums\EnumProductGenre;
use App\Models\Links\LinkProductGenre;
use App\Models\Links\LinkProductType;
use App\Models\Links\LinkUserProduct;
use App\Models\Notifications\Notification;
use App\Models\Products\Product;
use App\Models\Products\ProductAssessment;
use App\Models\Products\ProductComment;
use App\Models\Products\ProductsOrder;
use App\Models\Request\Request;
use App\Models\Users\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Psy\Util\Json;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductService implements ProductContract
{
    public function __construct(
        protected ProductCommentRepository      $productCommentRepository,
        protected ProductAssessmentRepository   $productAssessmentRepository,
        protected NotificationMessageRepository $notificationMessageRepository,
        protected NOtificationRepository        $notificationRepository,
        protected ProductDiscountService        $productDiscountService,
        protected ProductRepository             $productRepository,
        protected RequestRepository             $requestRepository,
        protected Transaction                   $transaction,
    )
    {
    }

    /**
     * @param ProductCreateRequest $productCreateRequest
     * @param int|null $id
     * @return int
     */
    public function createRequest(ProductCreateRequest $productCreateRequest, ?int $id = null): int
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (empty($user->merchant)) {
            abort(403, __('Merchant is not defined'));
        }

        $request = is_null($id) ? new Request() : $this->requestRepository->get($id);

        if (!empty($id) && !$request->isIsEditable()) {
            throw new BadRequestHttpException(__('client.Request doesnt editable'));
        }

        $request->setStatus(RequestStatusEnum::_CHECKING->value);
        $request->setData(Json::encode($productCreateRequest->validated()));
        $request->setStepId(4);
        $request->setModel(Product::class);
        $request->setAuthorId($user->getId());
        $request->setRequestTypeId(RequestTypeEnum::_PRODUCT->value);
        $request->setIsAgree((bool)$productCreateRequest->post('is_agree'));

        $this->transaction->wrap(function () use ($request, $user) {
            $this->requestRepository->save($request);

            $notificationMessage = $this->notificationMessageRepository->getByTypeId(4);

            $notification = new Notification();
            $notification->setModel(Request::class);
            $notification->setUserId($user->getId());
            $notification->setNotificationMessageId($notificationMessage->getId());
            $notification->setNotificationTypeId(2);
            $notification->setMessageUz($notificationMessage->getMessageUz());
            $notification->setMessageOz($notificationMessage->getMessageOz());
            $notification->setMessageRu($notificationMessage->getMessageRu());
            $notification->setApplyId((string)$user->getId());
            $notification->setApplyLink('product/view-request/' . $request->getId());
            $this->notificationRepository->save($notification);
        });

        return $request->getId();
    }

    /**
     * @param FormRequest $formRequest
     * @param int $id
     * @return bool
     */
    public function assessment(FormRequest $formRequest, int $id): bool
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $product = $this->productRepository->getById($id);

        if (!empty($this->productAssessmentRepository->findByUserAndProduct($user->getId(), $product->getId()))) {
            abort(403, __('client.Already assessment to product'));
        }

        $assessmentProduct = new ProductAssessment();
        $assessmentProduct->setProductId($product->getId());
        $assessmentProduct->setLevel((int)$formRequest->post('level'));
        $assessmentProduct->setAuthorId($user->getId());
        $this->productAssessmentRepository->save($assessmentProduct);

        $commentProduct = new ProductComment();
        $commentProduct->setProductId($product->getId());
        $commentProduct->setComment($formRequest->post('comment'));
        $commentProduct->setAuthorId($user->getId());
        $this->productCommentRepository->save($commentProduct);

        return true;
    }

    /**
     * @param FormRequest $formRequest
     * @param int $id
     * @return bool
     */
    public function comment(FormRequest $formRequest, int $id): bool
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $product = $this->productRepository->getById($id);

        $parent_id = $formRequest->post('parent_id') === null ? null : (int)$formRequest->post('parent_id');

        $commentProduct = new ProductComment();
        $commentProduct->setProductId($product->getId());
        $commentProduct->setComment($formRequest->post('comment'));
        $commentProduct->setAuthorId($user->getId());
        $commentProduct->setParentId($parent_id);
        $this->productCommentRepository->save($commentProduct);

        return true;
    }

    /**
     * @param int $id
     * @return array
     */
    public function assessmentList(int $id): array
    {
        return $this->productAssessmentRepository->findAllByProduct($id);
    }

    /**
     * @param FormRequest $formRequest
     * @param int $id
     * @return AnonymousResourceCollection
     */
    public function commentList(FormRequest $formRequest, int $id): AnonymousResourceCollection
    {
        return ProductCommentListResource::collection(ProductCommentSearchFilter::search($formRequest, $id));
    }

    /**
     * @param AuthorProductRequest $authorProductRequest
     * @return Collection|array
     */
    public function authorProductList(AuthorProductRequest $authorProductRequest): Collection|array
    {
        $authorProductRequest->validated();
        return $this->productRepository->getAuthorProductList($authorProductRequest->post('author_id'), $authorProductRequest->post('type_id'));
    }

    /**
     * @param int $productId
     * @return array
     */
    public function similarProducts(int $productId): array
    {
        $product = $this->productRepository->getById($productId);

        $productGenres = $product->productGenre;

        $productTypes = $product->productType;

        $genresIds = [];
        $typesIds = [];
        foreach ($productTypes as $productType) {
            /**
             * @var LinkProductType $productType
             */
            $typesIds[] = $productType->getTypeId();
        }

        foreach ($productGenres as $productGenre) {
            /**
             * @var EnumProductGenre $productGenre
             */
            $genresIds[] = $productGenre->getId();
        }

        /**
         * @var LinkProductGenreRepository $productGenreRepository
         */
        $productGenreRepository = app(LinkProductGenreRepository::class);

        /**
         * @var LinkProductTypeRepository $productTypeRepository
         */
        $productTypeRepository = app(LinkProductTypeRepository::class);

        $productTypes = $productTypeRepository->findAllById($typesIds, $productId);
        $productGenres = $productGenreRepository->findAllByIds($genresIds, $productId);

        $productIds = [];
        foreach ($productGenres as $productGenre) {
            $productIds[] = $productGenre->getProductId();
        }

        foreach ($productTypes as $productType) {
            $productIds[] = $productType->getProductId();
        }

        $productIds = array_unique($productIds);

        return $this->productRepository->findAllByIds($productIds, 5);
    }


    /**
     * @param FormRequest $formRequest
     * @return array
     */
    public function favoriteProducts(FormRequest $formRequest): array
    {
        return FavoriteProductSearchFilter::search($formRequest);
    }

    /**
     * @param int $id
     * @return void
     */
    public function buy(int $id): void
    {
        /**
         * @var ProductRepository $productRepository
         */
        $productRepository = app(ProductRepository::class);

        $product = $productRepository->getById($id);

        if (!Auth::check()) {
            abort(403, __('client.Unauthorized'));
        }


        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var ProductsOrderRepository $productsOrderRepository
         */
        $productsOrderRepository = app(ProductsOrderRepository::class);

        if ($productsOrderRepository->existsProductOrder($product->getId(), $user->getId())) {
            abort(400, __('client.Product already ordered'));
        }

        /**
         * @var ProductsOrderService $productsOrderService
         */
        $productsOrderService = app(ProductsOrderService::class);
        $productsOrderService->create(
            $product->getId(),
            PaymentTypeEnum::TYPE_FREE->value,
            Str::random(30), $user->getId(),
            0,
            0,
            0
        );
    }

    /**
     * @return LengthAwarePaginator
     */
    public function myList(): LengthAwarePaginator
    {
        if (!Auth::check()) {
            abort(403, __('client.Unauthorized'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        return ProductsOrderFilter::search($user->getId());
    }
}
