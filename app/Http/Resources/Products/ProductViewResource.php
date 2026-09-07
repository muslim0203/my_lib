<?php

namespace App\Http\Resources\Products;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\LInks\LinkProductCategoryRepository;
use App\Core\Repository\LInks\LinkProductFileRepository;
use App\Core\Repository\Product\ProductAssessmentRepository;
use App\Core\Repository\Product\ProductsOrderRepository;
use App\Http\Resources\Authors\AuthorityListResource;
use App\Http\Resources\Authors\AuthorListResource;
use App\Http\Resources\Enums\CategoryViewResource;
use App\Http\Resources\FileManager\FileViewResource;
use App\Http\Resources\Links\LinkProductFileListResource;
use App\Models\Authority\Authority;
use App\Models\Authors\Author;
use App\Models\Links\LinkProductCategories;
use App\Models\Products\Product;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Product
 */
class ProductViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discountPrice = null;

        /**
         * @var LinkProductCategoryRepository $linkCategoryRepository
         */
        $linkCategoryRepository = app(LinkProductCategoryRepository::class);
        $linkCategories = $linkCategoryRepository->findAllByProductId($this->getId());

        $categories = [];
        foreach ($linkCategories as $linkCategory) {
            /**
             * @var LinkProductCategories $linkCategory
             */
            $categories[] = new CategoryViewResource($linkCategory->category);
        }

        /**
         * @var ProductAssessmentRepository $productAssessmentRepository
         */
        $productAssessmentRepository = app(ProductAssessmentRepository::class);
        /**
         * @var LinkProductFileRepository $linkProductFileRepository
         */
        $linkProductFileRepository = app(LinkProductFileRepository::class);

        if (!empty($this->discount)) {
            $discountPrice = (int)$this->getPriceValue() - ceil(($this->discount->getDiscount() * (int)$this->getPriceValue()) / 100);
        }

        $author = [];
        if (!empty($this->author?->merchant?->model)) {

            if ($this->author?->merchant?->model instanceof Author) {
                $author = new AuthorListResource($this->author?->merchant?->model);
            } else if ($this->author?->merchant?->model instanceof Authority) {
                $author = new AuthorityListResource($this->author?->merchant?->model);
            }

        }

        $assessment = $productAssessmentRepository->averageAmountByProduct($this->getId());

        if (!empty($assessment)) {
            $assessment = round($assessment->getLevel() / $assessment->amount, 1);
        }

        $assessment_level = null;

        $sourceFile = false;

        $audioFile = false;

        $isAllow = false;
        if (Auth::check()) {
            /**
             * @var User $user
             */
            $user = Auth::user();

            $productAssessment = $productAssessmentRepository->findByUserAndProduct($user->getId(), $this->getId());

            /**
             * @var ProductsOrderRepository $productsOrderRepository
             */
            $productsOrderRepository = app(ProductsOrderRepository::class);

            // Order mavjudligining o'zi yetarli emas: pullik mahsulot uchun
            // to'lov tasdiqlangan bo'lishi shart (hasEntitlement).
            $isEntitled = $productsOrderRepository->hasEntitlement($this->resource, $user->getId());

            if ($isEntitled) {
                $isAllow = true;
                $sourceFile = true;
            }

            if ($isEntitled && $linkProductFileRepository->exists($this->getId())) {
                $audioFile = true;
            }

            if (!empty($productAssessment)) {
                $assessment_level = $productAssessment->getLevel();
            }
        }

        $isFree = $this->resource->isFree();

        return [
            'id' => $this->getId(),
            'title' => $this->{LanguageHelper::getTitle()},
            'description' => $this->{LanguageHelper::getDescription()},
            'status_id' => $this->getStatusId(),
            'status' => $this->status?->{LanguageHelper::getName()},
            'parent_id' => $this->getParentId(),
            'parent' => $this->parent?->{LanguageHelper::getTitle()},
            'size' => $this->getSize(),
            'wrapper_file' => new FileViewResource($this->wrapperFile),
            'source_file' => ($isFree || $sourceFile) ? new FileViewResource($this->sourceFile) : null,
            'state' => $this->getState(),
            'price_id' => $this->getPriceId(),
            'price' => $this->price?->{LanguageHelper::getName()},
            'price_type_id' => $this->getPriceTypeId(),
            'price_type' => $this->priceType?->{LanguageHelper::getName()},
            'last_update_date' => $this->getLastUpdatedDate(),
            'create_date' => $this->getCreateDate(),
            'extra_authors' => $this->getExtraAuthors(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'price_value' => (int)$this->getPriceValue(),
            'discount' => $this->discount?->getDiscount(),
            'discount_price' => $discountPrice,
            'author' => $author,
            'author_id' => $this->getAuthorId(),
            'assessment' => $assessment,
            'assessment_level' => $assessment_level,
            'is_allow' => $isAllow,
            'audio_files' => $audioFile ? LinkProductFileListResource::collection($linkProductFileRepository->findProductFileList($this->getId())) : null,
            'is_free' => $isFree,
            'categories' => $categories,
            'views_count' => $this->getViewsCount(),
            'is_download' => $this->isIsDownload()
        ];
    }
}
