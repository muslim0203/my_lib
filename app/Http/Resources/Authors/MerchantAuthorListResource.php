<?php

namespace App\Http\Resources\Authors;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\LInks\LinkAuthorSubscriberRepository;
use App\Core\Repository\Product\ProductRepository;
use App\Http\Resources\Merchant\ProfileFileViewResource;
use App\Http\Resources\Products\ProductListResource;
use App\Models\Authority\Authority;
use App\Models\Authors\Author;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin User
 */
class MerchantAuthorListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isSubscribe = false;

        /**
         * @var User|null $user
         */
        $user = Auth::user();

        if (!empty($user)) {

            /**
             * @var LinkAuthorSubscriberRepository $subscribeRepository
             */
            $subscribeRepository = app(LinkAuthorSubscriberRepository::class);

            if ($subscribeRepository->checkSubscribe($this->getId(), $user->getId())) {
                $isSubscribe = true;
            }

        }

        $merchant = $this->merchant;

        if (empty($merchant)) {
            return [];
        }

        $author = $merchant->model;

        $data = [];

        if ($author instanceof Author) {
            $data = [
                'id' => $this->getId(),
                'type' => 'author',
                'full_name' => $author->getFullName(),
                'description' => $author->getDescription(),
                'academic_degree' => $author->academicDegree?->{LanguageHelper::getName()},
                'academic_position' => $author->academicPosition?->{LanguageHelper::getName()},
                'education_type' => $author->educationType?->{LanguageHelper::getName()},
                'profile_file' => new ProfileFileViewResource($author->profileFile)
            ];
        } else if ($author instanceof Authority) {
            $data = [
                'id' => $this->getId(),
                'type' => 'authority',
                'name' => $author->{LanguageHelper::getName()},
                'description' => $author->getDescription(),
                'profile_file' => new ProfileFileViewResource($author->profileFile)
            ];
        }

        $productRepository = new ProductRepository();
        $products = ProductListResource::collection($productRepository->findAllByUserId($this->getId()));

        return array_merge($data, ['books' => $products->jsonSerialize(), 'isSubscribe' => $isSubscribe, 'subscribe_count' => $this->subscribers()->count()]);
    }
}
