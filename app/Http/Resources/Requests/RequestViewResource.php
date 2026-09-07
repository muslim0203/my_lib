<?php

namespace App\Http\Resources\Requests;

use App\Core\Enums\Requests\RequestTypeEnum;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\Enum\ActivityTypeRepository;
use App\Core\Repository\Enum\CategoriesRepository;
use App\Core\Repository\FileManager\FileManagerRepository;
use App\Http\Resources\Enums\AcademicDegreeListResource;
use App\Http\Resources\Enums\AcademicPositionListResource;
use App\Http\Resources\Enums\ActivityTypeViewResource;
use App\Http\Resources\Enums\CategoryViewResource;
use App\Http\Resources\Enums\EducationTypeListResource;
use App\Http\Resources\Enums\EnumCategoriesResource;
use App\Http\Resources\FileManager\FileViewResource;
use App\Http\Resources\Productions\ProductGenreListResource;
use App\Http\Resources\Productions\ProductTagListResource;
use App\Http\Resources\Productions\ProductTypeListResource;
use App\Models\Authority\Authority;
use App\Models\Authors\Author;
use App\Models\Enums\EnumAcademicDegree;
use App\Models\Enums\EnumAcademicPosition;
use App\Models\Enums\EnumCategories;
use App\Models\Enums\EnumEducationType;
use App\Models\Enums\EnumProductGenre;
use App\Models\Enums\EnumProductTag;
use App\Models\Enums\EnumProductType;
use App\Models\Products\Product;
use App\Models\Request\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Request
 */
class RequestViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        $data = json_decode($this->getData(), true);

        $extraData = [];
        /**
         * @var FileManagerRepository $fileRepository
         */
        $fileRepository = app(FileManagerRepository::class);

        if (!empty($data['file_id'])) {
            $file = new FileViewResource($fileRepository->getById($data['file_id']));
            unset($data['file_id']);
            unset($data['file_name']);

            $extraData['file'] = $file;
        }

        if (!empty($data['certificate_file_id'])) {
            $certificateFile = new FileViewResource($fileRepository->getById($data['certificate_file_id']));
            unset($data['certificate_file_id']);
            unset($data['certificate_file_name']);

            $extraData['certificate_file'] = $certificateFile;
        }

        if (!empty($data['audio_files'])) {
            foreach ($data['audio_files'] as $audio_file) {
                $extraData['audio_files'][] = new FileViewResource($fileRepository->getById($audio_file));
            }

            unset($data['audio_files']);
        }

        if (!empty($data['activity_type_id'])) {
            $activityType = (new ActivityTypeRepository())->get($data['activity_type_id']);
            $extraData['activity_type'] = new ActivityTypeViewResource($activityType);
        }

        if (!empty($data['activity_sphere_ids'])) {
            $activitySpheres = (new CategoriesRepository())->findByIds($data['activity_sphere_ids']);
            $extraData['activity_sphere'] = CategoryViewResource::collection($activitySpheres);
        }

        if ($this->getRequestTypeId() === RequestTypeEnum::_PRODUCT->value) {
            if (!empty($data['tags'])) {
                $tags = EnumProductTag::query()
                    ->whereIn('id', $data['tags'])
                    ->get()
                    ->all();

                $tags = ProductTagListResource::collection($tags)->toArray($request);

                $extraData['tag_list'] = $tags;
            }

            if (!empty($data['categories'])) {
                $categories = EnumCategories::query()
                    ->whereIn('id', $data['categories'])
                    ->get()
                    ->all();

                $categories = EnumCategoriesResource::collection($categories)->toArray($request);

                $extraData['category_list'] = $categories;
            }

            if (!empty($data['types'])) {
                $types = EnumProductType::query()
                    ->whereIn('id', $data['types'])
                    ->get()
                    ->all();

                $types = ProductTypeListResource::collection($types)->toArray($request);

                $extraData['type_list'] = $types;
            }

            if (!empty($data['genres'])) {
                $genres = EnumProductGenre::query()
                    ->whereIn('id', $data['genres'])
                    ->get()
                    ->all();

                $genres = ProductGenreListResource::collection($genres)->toArray($request);

                $extraData['genre_list'] = $genres;
            }
        }

        if (!empty($data['wrapper_file_id'])) {
            $wrapper_file = new FileViewResource($fileRepository->getById($data['wrapper_file_id']));
            unset($data['wrapper_file_id']);
            unset($data['wrapper_file_name']);

            $extraData['wrapper_file'] = $wrapper_file;
        }

        if (!empty($data['source_file_id'])) {
            $source_file = new FileViewResource($fileRepository->getById($data['source_file_id']));
            unset($data['source_file_id']);
            unset($data['source_file_name']);

            $extraData['source_file'] = $source_file;
        }

        if (!empty($data['academic_position_id'])) {
            $academic_position = EnumAcademicPosition::query()
                ->where('id', $data['academic_position_id'])
                ->get()
                ->all();

            $extraData['academic_position'] = AcademicPositionListResource::collection($academic_position)->toArray($request);
        }

        if (!empty($data['academic_degree_id'])) {
            $academic_degree = EnumAcademicDegree::query()
                ->where('id', $data['academic_degree_id'])
                ->get()
                ->all();

            $extraData['academic_degree'] = AcademicDegreeListResource::collection($academic_degree)->toArray($request);
        }

        if (!empty($data['diploma_file_id'])) {
            $academic_degree = $fileRepository->getById($data['diploma_file_id']);

            $extraData['diploma_file'] = new FileViewResource($academic_degree);
        }

        if (!empty($data['licence_file_id'])) {
            $academic_degree = $fileRepository->getById($data['licence_file_id']);

            $extraData['licence_file'] = new FileViewResource($academic_degree);
        }

        if (!empty($data['education_type_id'])) {
            $education_type = EnumEducationType::query()
                ->where('id', $data['education_type_id'])
                ->first();

            $extraData['education_type'] = new EducationTypeListResource($education_type);
        }

        $editable_api = null;

        if ($this->getModel() === Authority::class) {
            $editable_api = 'authority';
        } else if ($this->getModel() === Author::class) {
            $editable_api = 'author';
        } else if ($this->getModel() === Product::class) {
            $editable_api = 'product';
        }

        return array_merge_recursive([
            'id' => $this->getId(),
            'editable_api' => $editable_api,
            'model' => $this->getModel(),
            'confirm_author_id' => $this->confirmAuthor?->employee?->getFirstName(),
            'reject_author_id' => $this->rejectAuthor?->employee?->getFirstName(),
            'status' => $this->getStatus(),
            'request_type_id' => $this->getRequestTypeId(),
            'request_type' => $this->requestType?->{LanguageHelper::getName()},
            'step_id' => $this->getStepId(),
            'step' => $this->step?->{LanguageHelper::getName()},
            'comment' => $this->comment,
            'is_editable' => $this->isIsEditable()
        ], $data, $extraData);
    }
}
