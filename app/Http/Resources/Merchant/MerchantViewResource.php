<?php

namespace App\Http\Resources\Merchant;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Http\Resources\FileManager\FileViewResource;
use App\Models\Authority\Authority;
use App\Models\Authors\Author;
use App\Models\Users\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function PHPUnit\Framework\matches;

/**
 * @mixin Merchant
 */
class MerchantViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $author = new Author();
        $authority = new Authority();

        $merchantAuthor = $this->model;

        if (empty($merchantAuthor)) {
            return [];
        }

        $result = match ($merchantAuthor->getTable()) {
            $authority->getTable() => [
                'authority_id' => $merchantAuthor->getId(),
                'name' => $merchantAuthor->{LanguageHelper::getName()},
                'inn' => $merchantAuthor->getInn(),
                'email' => $merchantAuthor->getEmail(),
                'address' => $merchantAuthor->getAddress(),
                'phone' => $merchantAuthor->getPhone(),
                'account_number' => $merchantAuthor->getAccountNumber(),
                'description' => $merchantAuthor->getDescription(),
                'profile_file' => new ProfileFileViewResource($merchantAuthor->profileFile),
                'certificate_file' => new FileViewResource($merchantAuthor->certificateFile),
                'status' => $merchantAuthor->getStatus(),
                'step' => $merchantAuthor->step->{LanguageHelper::getName()},
            ],
            $author->getTable() => [
                'author_id' => $merchantAuthor->getId(),
                'full_name' => $merchantAuthor->getFullName(),
                'email' => $merchantAuthor->getEmail(),
                'phone' => $merchantAuthor->getPhone(),
                'gender' => $merchantAuthor->getGender(),
                'passport' => $merchantAuthor->getPassport(),
                'pin_fl' => $merchantAuthor->getPinFl(),
                'description' => $merchantAuthor->getDescription(),
                'account_number' => $merchantAuthor->getAccountNumber(),
                'status' => $merchantAuthor->getStatus(),
                'academic_degree' => $merchantAuthor->academicDegree?->{LanguageHelper::getName()},
                'academic_position' => $merchantAuthor->academicPosition?->{LanguageHelper::getName()},
                'education_type' => $merchantAuthor->educationType?->{LanguageHelper::getName()},
                'step' => $merchantAuthor->step->{LanguageHelper::getName()},
                'profile_file' => new ProfileFileViewResource($merchantAuthor->profileFile),
                'diploma_file' => new FileViewResource($merchantAuthor->diplomaFile),
                'licence_file' => new FileViewResource($merchantAuthor->licenceFile),
            ],
            default => []
        };

        return [
            'id' => $this->id,
            'type' => $merchantAuthor->getTable(),
            $merchantAuthor->getTable() => $result
        ];
    }
}
