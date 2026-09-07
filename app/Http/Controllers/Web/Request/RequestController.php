<?php

namespace App\Http\Controllers\Web\Request;

use App\Core\Filters\Request\RequestSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Services\Request\RequestService;
use App\Http\Requests\Requests\ConfirmOrCancelRequest;
use App\Http\Requests\Requests\RequestSearchFilterForm;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class RequestController extends Controller
{
    /**
     * @param RequestSearchFilterForm $requestSearchFilterForm
     * @param int|null $type
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(RequestSearchFilterForm $requestSearchFilterForm, ?int $type = null): Factory|View|\Illuminate\Foundation\Application|Application
    {
        $data = RequestSearchFilter::search($requestSearchFilterForm, $type, true);

        return view('pages.requests.filter', compact('data'),[
            'type' => $type
        ]);
    }

    /**
     * @param RequestRepository $requestRepository
     * @param int $id
     * @param int $type
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function view(RequestRepository $requestRepository, int $id,int $type): Factory|View|\Illuminate\Foundation\Application|Application
    {
        $request = $requestRepository->get($id);

        return view('pages.requests.view', compact('request'), [
            'data' => $requestRepository->getData($request->id),
            'type' => $type
        ]);
    }

    /**
     * @param ConfirmOrCancelRequest $confirmOrCancelRequest
     * @param int $id
     * @param RequestService $requestService
     * @return JsonResponse
     */
    public function confirm(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id, RequestService $requestService):JsonResponse
    {
        if (!$requestService->confirmRequest($confirmOrCancelRequest,$id)) {
            Success::error(__('client.Unknown error'));
        }

        return Success::send('Successful done');
    }

    /**
     * @param ConfirmOrCancelRequest $confirmOrCancelRequest
     * @param int $id
     * @param RequestService $requestService
     * @return JsonResponse
     */
    public function reject(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id, RequestService $requestService): JsonResponse
    {
        if (!$requestService->rejectRequest($confirmOrCancelRequest,$id)) {
            Success::error(__('client.Unknown error'));
        }

        return Success::send('Successful done');
    }
}
