<?php

namespace App\Http\Controllers\Api\User;

use App\Core\Helpers\Response\Success;
use App\Core\Services\User\AccountDeletionService;
use App\Core\Services\User\Interfaces\UserInterface;
use App\Http\Requests\User\SelectInterestRequest;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Resources\Users\UserInterestListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
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

    /**
     * Akkauntni o'chirish (Xaridorlar ofertasi 3.4-band). Tasodifiy
     * bosilishdan himoya uchun `confirm: true` talab qilinadi.
     *
     * @param Request $request
     * @param AccountDeletionService $accountDeletionService
     * @return JsonResponse
     */
    public function deleteAccount(Request $request, AccountDeletionService $accountDeletionService): JsonResponse
    {
        $request->validate(['confirm' => ['required', 'accepted']]);

        /** @var \App\Models\Users\User $user */
        $user = Auth::user();

        if ($accountDeletionService->isSeller($user)) {
            abort(409, __('client.Seller account must be closed through support'));
        }

        $accountDeletionService->delete($user);

        try {
            JWTAuth::parseToken()->invalidate(true);
        } catch (Throwable) {
            // Token baribir ishlamaydi: o'chirilgan foydalanuvchini JWT
            // provayderlari qabul qilmaydi.
        }

        return Success::send('Account deleted');
    }
}
