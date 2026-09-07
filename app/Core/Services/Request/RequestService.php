<?php

namespace App\Core\Services\Request;

use App\Core\Enums\Requests\RequestStatusEnum;
use App\Core\Enums\Requests\RequestTypeEnum;
use App\Core\Helpers\Transaction;
use App\Core\Repository\Product\ProductStatusRepository;
use App\Core\Repository\Request\RequestRepository;
use App\Core\Repository\Step\ProcessStepRepository;
use App\Core\Services\Request\Interface\RequestInterface;
use App\Http\Requests\Requests\ConfirmOrCancelRequest;
use App\Models\Authority\Authority;
use App\Models\Authority\AuthorityProfileFile;
use App\Models\Authors\Author;
use App\Models\Authors\AuthorProfileFile;
use App\Models\Links\LinkAuthorityActivitySphere;
use App\Models\Links\LinkProductCategories;
use App\Models\Links\LinkProductFiles;
use App\Models\Links\LinkProductGenre;
use App\Models\Links\LinkProductTag;
use App\Models\Links\LinkProductType;
use App\Models\Products\Product;
use App\Models\Products\ProductDiscount;
use App\Models\Users\Merchant;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Auth;

class RequestService implements RequestInterface
{

    public function __construct(
        protected RequestRepository       $requestRepository,
        protected ProcessStepRepository   $processStepRepository,
        protected Transaction             $transaction,
        protected ProductStatusRepository $statusRepository
    )
    {
    }

    public function confirmRequest(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id)
    {
        $request = $this->requestRepository->get($id);
        $user = Auth::user();
        return $this->transaction->wrap(function () use ($confirmOrCancelRequest, $request, $user) {

            $data = Json::decode($request->data);

            switch ($request->getRequestTypeId()) {
                case RequestTypeEnum::_PRODUCT->value:

                    $product = new Product();
                    $product->fill($data);
                    $product->setRequestId($request->getId());

                    $status = $this->statusRepository->getCode('NEW');
                    $product->setStatusId($status->id);
                    $product->setAuthorId($request->getAuthorId());
                    $product->setConfirmAuthorId($user->getId());

                    $processStep = $this->processStepRepository->getByCodeName('confirmed_add_new_product_request');
                    $product->setStepId($processStep->getId());
                    $product->setState('new');
                    $product->setCreateDate(date('Y-m-d H:i:s'));
                    $product->setExtraAuthors($data['extra_authors'] ?? '');
                    $product->setHasAudioFile(!empty($data['audio_files']));

                    $product->save();

                    $this->saveLinkCategories($product->getId(), $data['categories']);

                    $this->saveLinkTags($product->getId(), $data['tags']);

                    $this->saveLinkGenres($product->getId(), $data['genres']);

                    $this->saveLinkTypes($product->getId(), $data['types']);

                    if (!empty($data['audio_files'])) $this->saveAudioFiles($product->getId(), $data['audio_files']);

                    if (!empty($data['discount'])) {
                        $discountId = $this->saveDiscount($product->getId(), $data['discount']);
                        $product->setDiscountId($discountId);
                        $product->save();
                    }

                    $request->setComment($confirmOrCancelRequest->post('description'));
                    $request->setModelId($product->getId());
                    $request->setStatus(RequestStatusEnum::_APPROVED->value);
                    $request->setIsEditable(false);
                    $request->save();
                    break;
                case RequestTypeEnum::_AUTHOR->value:

                    $processStep = $this->processStepRepository->getByCodeName('confirmed_add_new_author');
                    $author = new Author();
                    $author->fill($data);
                    $author->setRequestId($request->getId());

                    $author->setStepId($processStep->getId());
                    $author->setUserId($request->getAuthorId());

                    $author->save();

                    if (!empty($data['file_id'])) {
                        $profileFileId = $this->saveAuthorProfileFile($author->getId(), $data);
                        $author->setProfileFileId($profileFileId);
                        $author->save();
                    }

                    $merchant = new Merchant();
                    $merchant->setUserId($request->getAuthorId());
                    $merchant->setModelId($author->getId());
                    $merchant->setModelType(Author::class);

                    $merchant->save();

                    $request->setComment($confirmOrCancelRequest->post('description'));
                    $request->setStatus(RequestStatusEnum::_APPROVED->value);
                    $request->setModelId($author->getId());
                    $request->setStepId($processStep->getId());
                    $request->setIsEditable(false);

                    $request->save();

                    break;
                case RequestTypeEnum::_AUTHORITY->value:

                    $processStep = $this->processStepRepository->getByCodeName('confirmed_add_new_authority_request');
                    $authority = new Authority();
                    $authority->fill($data);
                    $authority->setRequestId($request->getId());

                    $authority->setStepId($processStep->getId());
                    $authority->setUserId($request->getAuthorId());

                    $authority->save();

                    if (!empty($data['activity_sphere_ids'])) {
                        foreach ($data['activity_sphere_ids'] as $activity_sphere_id) {
                            $model = new LinkAuthorityActivitySphere();
                            $model->setSphereId((int)$activity_sphere_id);
                            $model->setAuthorityId($authority->getId());
                            $model->save();
                        }
                    }

                    if (!empty($data['file_id'])) {
                        $profileFileId = $this->saveAuthorityProfileFile($authority->getId(), $data);
                        $authority->setProfileFileId($profileFileId);
                        $authority->save();
                    }

                    $merchant = new Merchant();
                    $merchant->setUserId($request->getAuthorId());
                    $merchant->setModelId($authority->getId());
                    $merchant->setModelType(Authority::class);

                    $merchant->save();

                    $request->setComment($confirmOrCancelRequest->post('description'));
                    $request->setStatus(RequestStatusEnum::_APPROVED->value);
                    $request->setModelId($authority->getId());
                    $request->setStepId($processStep->getId());
                    $request->setIsEditable(false);

                    $request->save();
                    break;
            }
        });

    }

    public function saveLinkCategories(int $id, array $categories): void
    {
        foreach ($categories as $category) {
            $linkCategory = new LinkProductCategories();
            $linkCategory->setProductId($id);
            $linkCategory->setCategoryId($category);
            $linkCategory->save();
        }
    }

    public function saveLinkTags(int $id, array $tags): void
    {
        foreach ($tags as $tag) {
            $linkTag = new LinkProductTag();
            $linkTag->setProductId($id);
            $linkTag->setTagId($tag);
            $linkTag->save();
        }
    }

    public function saveLinkGenres(int $id, array $genres): void
    {
        foreach ($genres as $genre) {
            $linkGenre = new LinkProductGenre();
            $linkGenre->setProductId($id);
            $linkGenre->setGenreId($genre);
            $linkGenre->save();
        }
    }

    public function saveLinkTypes(int $id, array $types): void
    {
        foreach ($types as $type) {
            $linkType = new LinkProductType();
            $linkType->setProductId($id);
            $linkType->setTypeId($type);
            $linkType->save();
        }
    }

    public function saveAudioFiles(int $id, array $files): void
    {
        $files = array_unique($files);

        foreach ($files as $file) {
            $linkFile = new LinkProductFiles();
            $linkFile->setProductId($id);
            $linkFile->setFileId($file);
            $linkFile->save();
        }
    }

    public function saveDiscount(int $id, ?int $discount = null): ?int
    {
        if (!empty($discount)) {
            $newDiscount = new ProductDiscount();
            $newDiscount->setProductId($id);
            $newDiscount->setDiscount($discount);
            $newDiscount->setFromExpireAt(date('Y-m-d H:i:s'));
            $newDiscount->setToExpireAt(date('Y-m-d H:i:s', strtotime('+30 days')));
            $newDiscount->save();
            return $newDiscount->getId();
        }

        return null;
    }

    /**
     * @param int $id
     * @param array $data
     * @return int|null
     */
    public function saveAuthorProfileFile(int $id, array $data): ?int
    {
        $authorProfileFile = new AuthorProfileFile();
        $authorProfileFile->setAuthorId($id);
        $authorProfileFile->setFileId($data['file_id']);
        $authorProfileFile->setFileName($data['file_name']);
        $authorProfileFile->save();

        return $authorProfileFile->getId();
    }

    public function saveAuthorityProfileFile(int $id, array $data): ?int
    {
        $authorityProfileFile = new AuthorityProfileFile();
        $authorityProfileFile->setAuthorityId($id);
        $authorityProfileFile->setFileId($data['file_id']);
        $authorityProfileFile->setFileName($data['file_name']);
        $authorityProfileFile->save();

        return $authorityProfileFile->getId();
    }

    public function rejectRequest(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id)
    {
        $request = $this->requestRepository->get($id);
        $user = Auth::user();
        return $this->transaction->wrap(function () use ($request, $confirmOrCancelRequest, $user) {

            switch ($request->getRequestTypeId()) {
                case RequestTypeEnum::_PRODUCT->value:
                    $processStep = $this->processStepRepository->getByCodeName('rejected_add_new_product_request');
                    $request->setStepId($processStep->getId());

                    break;
                case RequestTypeEnum::_AUTHOR->value:
                    $processStep = $this->processStepRepository->getByCodeName('revoked_add_new_author');
                    $request->setStepId($processStep->getId());

                    break;
                case RequestTypeEnum::_AUTHORITY->value:
                    $processStep = $this->processStepRepository->getByCodeName('rejected_add_new_authority_request');
                    $request->setStepId($processStep->getId());

                    break;

            }

            $request->setComment($confirmOrCancelRequest->post('description'));
            $request->setRejectAuthorId($user->getId());
            $request->setStatus(RequestStatusEnum::_REJECTED->value);

            $request->save();
        });

    }
}
