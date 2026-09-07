<?php

namespace App\Http\Controllers\Api\User;

use App\Core\Helpers\Response\Success;
use App\Core\Services\User\Interfaces\UserInterface;
use App\Http\Requests\User\SelectInterestRequest;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Resources\Users\UserInterestListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function __construct(
        protected UserInterface $userService
    )
    {
    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return Success::send('User detail view', $this->userService->list());
    }

    /**
     * @param UserEditRequest $userEditRequest
     * @return JsonResponse
     */
    public function edit(UserEditRequest $userEditRequest): JsonResponse
    {
        return Success::send('Successful done updated user', ['id' => $this->userService->edit($userEditRequest)]);
    }

    /**
     * @param SelectInterestRequest $selectInterestRequest
     * @return JsonResponse
     */
    public function selectInterest(SelectInterestRequest $selectInterestRequest): JsonResponse
    {
        if (!$this->userService->selectInterest($selectInterestRequest)) {
            return Success::send('Successful done updated user select interest');
        }

        return Success::send('Successful done user select interest');
    }

    /**
     * @return JsonResponse
     */
    public function interestList(): JsonResponse
    {
        return Success::send('Successful done user select interest', UserInterestListResource::collection($this->userService->interestList()));
    }
}
