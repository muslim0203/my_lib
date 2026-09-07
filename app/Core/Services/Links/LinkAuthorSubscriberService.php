<?php

namespace App\Core\Services\Links;

use App\Core\Helpers\Transaction;
use App\Core\Repository\LInks\LinkAuthorSubscriberRepository;
use App\Http\Requests\Links\SubscriberRequest;
use App\Models\Links\LinkAuthorSubscribers;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;

class LinkAuthorSubscriberService
{
    public function __construct(
        protected LinkAuthorSubscriberRepository $linkAuthorSubscriberRepository,
        protected Transaction                    $transaction
    )
    {
    }

    /**
     * @param SubscriberRequest $subscriberRequest
     * @return mixed
     */
    public function subscribe(SubscriberRequest $subscriberRequest)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        return $this->transaction->wrap(function () use ($subscriberRequest, $user) {
            if ($subscriberRequest->post('subscribe')) {


                if ($this->linkAuthorSubscriberRepository->checkSubscribe($subscriberRequest->post('author_id'), $user->getId())) {
                    abort(400, __('client.Already subscribed!'));
                }

                $model = new LinkAuthorSubscribers();
                $model->setAuthorId($subscriberRequest->post('author_id'));
                $model->setSubscriberId($user->getId());
                $this->linkAuthorSubscriberRepository->save($model);

            } else {
                if ($this->linkAuthorSubscriberRepository->checkSubscribe($subscriberRequest->post('author_id'), $user->getId())) {
                    $this->linkAuthorSubscriberRepository->delete($subscriberRequest->post('author_id'), $user->getId());
                }
            }
        });
    }
}
