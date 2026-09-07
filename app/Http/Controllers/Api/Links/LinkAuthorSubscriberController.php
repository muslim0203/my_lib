<?php

namespace App\Http\Controllers\Api\Links;

use App\Core\Helpers\Response\Success;
use App\Core\Repository\LInks\LinkAuthorSubscriberRepository;
use App\Core\Services\Links\LinkAuthorSubscriberService;
use App\Http\Requests\Links\SubscriberRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LinkAuthorSubscriberController extends Controller
{
    /**
     * @param Request $request
     * @param LinkAuthorSubscriberRepository $linkAuthorSubscriberRepository
     * @return JsonResponse
     */
    public function subscriberCount(Request $request, LinkAuthorSubscriberRepository $linkAuthorSubscriberRepository): JsonResponse
    {

        return Success::send("Send Author Subscriber Count", [
            'count' => $linkAuthorSubscriberRepository->subscriberCount($request->integer('author_id'))
        ]);
    }

    /**
     * @param SubscriberRequest $subscriberRequest
     * @param LinkAuthorSubscriberService $linkAuthorSubscriberService
     * @return JsonResponse
     */
    public function subscribe(SubscriberRequest $subscriberRequest, LinkAuthorSubscriberService $linkAuthorSubscriberService): JsonResponse
    {
        return Success::send("Subscriber Subscribed Successfully", $linkAuthorSubscriberService->subscribe($subscriberRequest));
    }
}
