<?php

namespace App\Http\Controllers\Api\Author;

use App\Core\Helpers\Response\Success;
use App\Core\Services\Register\AuthorRegisterService;
use App\Core\Services\Register\RegisterService;
use App\Http\Requests\Authors\AuthorCommentRequest;
use App\Http\Requests\Authors\AuthorEditRequest;
use App\Http\Requests\Authors\AuthorRegisterRequest;
use App\Http\Requests\Authors\AuthorShowRequest;
use App\Http\Resources\Authors\MerchantAuthorListResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class AuthorController extends Controller
{
    public function __construct(
        protected AuthorRegisterService $authorRegisterService
    )
    {
    }

    /**
     * @param AuthorRegisterRequest $authorRegisterRequest
     * @return JsonResponse
     */
    public function register(AuthorRegisterRequest $authorRegisterRequest): JsonResponse
    {
        $service = new RegisterService($this->authorRegisterService);
        $id = $service->register($authorRegisterRequest);
        return Success::send("Send request to moderator. Id number $id", ['id' => $id]);
    }

    /**
     * @param AuthorEditRequest $authorEditRequest
     * @return JsonResponse
     */
    public function edit(AuthorEditRequest $authorEditRequest): JsonResponse
    {
        $service = new RegisterService($this->authorRegisterService);
        return Success::send("Send new request to moderator", ['id' => $service->register($authorEditRequest)]);
    }

    public function showProfile(AuthorShowRequest $authorShowRequest): JsonResponse
    {
        return Success::send("Author Detail", new MerchantAuthorListResource($this->authorRegisterService->showProfile($authorShowRequest)));
    }

    public function commentList(FormRequest $formRequest, int $id): AnonymousResourceCollection
    {
        return $this->authorRegisterService->commentList($formRequest, $id);
    }

    public function comment(AuthorCommentRequest $commentRequest, int $id): JsonResponse
    {
        $this->authorRegisterService->comment($commentRequest, $id);

        return Success::send('Successfully comment of author');
    }
}
