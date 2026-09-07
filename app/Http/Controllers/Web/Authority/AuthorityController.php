<?php

namespace App\Http\Controllers\Web\Authority;

use App\Core\Filters\Authority\AuthoritySearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Services\Authority\AuthorityService;
use App\Http\Requests\Authority\AuthoritySearchFilterRequest;
use App\Http\Requests\Authors\ConfirmOrCancelRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class AuthorityController extends Controller
{
    /**
     * @param AuthoritySearchFilterRequest $authoritySearchFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(AuthoritySearchFilterRequest $authoritySearchFilterRequest): Factory|View|\Illuminate\Foundation\Application|Application
    {
        $data = AuthoritySearchFilter::search($authoritySearchFilterRequest);

        return view('pages.authority.filter', compact('data'));
    }

    /**
     * @param int $id
     * @param AuthorityRepository $authorityRepository
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function view(int $id, AuthorityRepository $authorityRepository): Factory|View|\Illuminate\Foundation\Application|Application
    {
        $data = $authorityRepository->getById($id);

        return view('pages.authority.view', compact('data'));
    }

    /**
     * @param ConfirmOrCancelRequest $confirmOrCancelRequest
     * @param int $id
     * @param AuthorityService $authorityService
     * @return JsonResponse
     */
    public function cancel(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id, AuthorityService $authorityService): JsonResponse
    {
        if (!$authorityService->confirmOrCancel($confirmOrCancelRequest, $id, false)) {
            abort(400, __('client.Unknown error'));
        }

        return Success::send('Successful done');
    }

    /**
     * @param ConfirmOrCancelRequest $confirmOrCancelRequest
     * @param int $id
     * @param AuthorityService $authorityService
     * @return JsonResponse
     */
    public function confirm(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id, AuthorityService $authorityService): JsonResponse
    {
        if (!$authorityService->confirmOrCancel($confirmOrCancelRequest, $id)) {
            abort(400, __('client.Unknown error'));
        }

        return Success::send('Successful done');
    }
}
