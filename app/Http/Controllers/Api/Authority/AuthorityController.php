<?php

namespace App\Http\Controllers\Api\Authority;

use App\Core\Helpers\Response\Success;
use App\Core\Services\Register\AuthorityRegisterService;
use App\Core\Services\Register\RegisterService;
use App\Http\Requests\Authority\AuthorityRegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class AuthorityController extends Controller
{

    /**
     * @param AuthorityRegisterRequest $authorityRegisterRequest
     * @return JsonResponse
     */
    public function register(AuthorityRegisterRequest $authorityRegisterRequest): JsonResponse
    {
        $registerService = new RegisterService(new AuthorityRegisterService());

        $id = $registerService->register($authorityRegisterRequest);

        return Success::send('Send request to moderator. Request id = ' . $id, [
            'id' => $id
        ]);
    }

    /**
     * @param AuthorityRegisterRequest $authorityRegisterRequest
     * @return JsonResponse
     */
    public function editRegister(AuthorityRegisterRequest $authorityRegisterRequest): JsonResponse
    {
        $registerService = new RegisterService(new AuthorityRegisterService());

        $id = $registerService->register($authorityRegisterRequest);

        return Success::send('Send new request to moderator. Request id = ' . $id);
    }
}
