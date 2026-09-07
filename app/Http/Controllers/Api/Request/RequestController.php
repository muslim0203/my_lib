<?php

namespace App\Http\Controllers\Api\Request;

use App\Core\Enums\Requests\RequestStatusEnum;
use App\Core\Filters\Request\RequestSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Notification\NotificationRepository;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Services\Product\ProductService;
use App\Core\Services\Register\AuthorityRegisterService;
use App\Core\Services\Register\AuthorRegisterService;
use App\Core\Services\Register\RegisterService;
use App\Http\Requests\Authority\AuthorityRegisterRequest;
use App\Http\Requests\Authors\AuthorRegisterRequest;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Requests\RequestSearchFilterForm;
use App\Http\Resources\Requests\RequestListResource;
use App\Http\Resources\Requests\RequestViewResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct(
        protected NotificationRepository $notificationRepository,
        protected ProductService         $productService
    )
    {
    }

    /**
     * @return array
     */
    public function status(): array
    {
        return RequestStatusEnum::getListLabel();
    }

    /**
     * @param RequestSearchFilterForm $requestSearchFilterForm
     * @return AnonymousResourceCollection
     */
    public function list(RequestSearchFilterForm $requestSearchFilterForm): AnonymousResourceCollection
    {
        return RequestListResource::collection(RequestSearchFilter::search($requestSearchFilterForm));
    }

    /**
     * @param int $id
     * @param RequestRepository $requestRepository
     * @return JsonResponse
     */
    public function view(int $id, RequestRepository $requestRepository): JsonResponse
    {
        /**
         * @var User|null $user
         */
        $user = Auth::user();

        if (empty($user)) {
            abort(403, __('client.Unauthorized'));
        }

        // Ariza ichida passport, PINFL va hisob raqami bor. Moderator
        // (xodim) barcha arizalarni ko'radi, oddiy foydalanuvchi esa
        // faqat o'zinikini.
        $request = empty($user->employee_id)
            ? $requestRepository->getOwned($id, $user->getId())
            : $requestRepository->get($id);

        return Success::send('Request view', new RequestViewResource($request));
    }

    /**
     * @param AuthorityRegisterRequest $authorityRegisterRequest
     * @param int|null $id
     * @return JsonResponse
     */
    public function createAuthority(
        AuthorityRegisterRequest $authorityRegisterRequest,
        ?int                     $id = null
    ): JsonResponse
    {
        $registerService = new RegisterService(new AuthorityRegisterService());

        $id = $registerService->register($authorityRegisterRequest, $id);

        return Success::send('Send request to moderator. Request id = ' . $id, [
            'id' => $id
        ]);
    }

    /**
     * @param AuthorRegisterRequest $authorRegisterRequest
     * @param AuthorRegisterService $authorRegisterService
     * @param int|null $id
     * @return JsonResponse
     */
    public function createAuthor(
        AuthorRegisterRequest $authorRegisterRequest,
        AuthorRegisterService $authorRegisterService,
        ?int                  $id = null
    ): JsonResponse
    {
        $service = new RegisterService($authorRegisterService);

        $id = $service->register($authorRegisterRequest, $id);

        return Success::send("Send request to moderator. Id number $id", ['id' => $id]);
    }

    /**
     * @param ProductCreateRequest $productCreateRequest
     * @param int|null $id
     * @return JsonResponse
     */
    public function createProduct(ProductCreateRequest $productCreateRequest, ?int $id = null): JsonResponse
    {
        $id = $this->productService->createRequest($productCreateRequest, $id);

        return Success::send('Successfully created new product request. Request number: ' . $id, [
            'id' => $id
        ]);
    }
}
