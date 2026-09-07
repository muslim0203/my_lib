<?php

namespace App\Http\Resources\Products;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\Product\ProductAssessmentRepository;
use App\Http\Resources\Authors\AuthorityListResource;
use App\Http\Resources\Authors\AuthorListResource;
use App\Http\Resources\FileManager\FileViewResource;
use App\Models\Authority\Authority;
use App\Models\Authors\Author;
use App\Models\Products\Product;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Product
 */
class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var ProductAssessmentRepository $productAssessmentRepository
         */
        $productAssessmentRepository = app(ProductAssessmentRepository::class);

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
        if (Auth::check()) {
            /**
             * @var User $user
             */
            $user = Auth::user();

            $productAssessment = $productAssessmentRepository->findByUserAndProduct($user->getId(), $this->getId());

            if (!empty($productAssessment)) {
                $assessment_level = $productAssessment->getLevel();
            }
        }

        $discountPrice = null;

        if (!empty($this->discount)) {
            $discountPrice = (int)$this->getPriceValue() - ceil(($this->discount->getDiscount() * (int)$this->getPriceValue()) / 100);
        }

        return [
            'id' => $this->getId(),
            'title' => $this->{LanguageHelper::getTitle()},
            'author' => $author,
            'author_id' => $this->getAuthorId(),
            'status' => $this->getStatusId(),
            'status_text' => $this->status?->{LanguageHelper::getName()},
            'wrapper_file' => new FileViewResource($this->wrapperFile),
            'assessment' => $assessment,
            'assessment_level' => $assessment_level,
            'price_value' => (int)$this->getPriceValue(),
            'discount_price' => $discountPrice,
            'discount' => $this->discount?->getDiscount(),
            'views_count' => $this->getViewsCount(),
            'has_audio_file' => $this->isHasAudioFile(),
            'is_download' => $this->isIsDownload()
        ];
    }
}
