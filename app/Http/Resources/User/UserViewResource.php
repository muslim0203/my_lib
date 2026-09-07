<?php

namespace App\Http\Resources\User;

use App\Core\Enums\Auth\LoginTypeEnum;
use App\Core\Enums\Requests\RequestStatusEnum;
use App\Core\Enums\Requests\RequestTypeEnum;
use App\Core\Repository\Request\RequestRepository;
use App\Http\Resources\Merchant\MerchantViewResource;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin User
 */
class UserViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $args = [];

        if ($this->getLoginType() === LoginTypeEnum::_LOGIN_EMAIL->value) {
            $args = [
                'email' => $this->getEmail(),
            ];
        } else if ($this->getLoginType() === LoginTypeEnum::_LOGIN_GOOGLE->value) {
            $args = [
                'email' => $this->getEmail(),
            ];
        } else if ($this->getLoginType() === LoginTypeEnum::_LOGIN_SMS->value) {
            $args = [
                'phone' => $this->getPhone(),
            ];
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $requestRepository = new RequestRepository();

        $request = $requestRepository->findByAuthor($user->getId(), [
            RequestTypeEnum::_AUTHOR->value,
            RequestTypeEnum::_AUTHORITY->value
        ]);
        $merchant = null;
        if (
            !empty($request)
        ) {
            if ($request->getStatus() === RequestStatusEnum::_APPROVED->value) {
                $merchant = new MerchantViewResource($user->merchant);
            }

            if ($request->getStatus() === RequestStatusEnum::_CHECKING->value) {
                $merchant = RequestStatusEnum::_CHECKING->value;
            }
        }

        return array_merge_recursive($args, [
            'id' => $this->getId(),
            'first_name' => $this->socialUser?->getFirstName(),
            'last_name' => $this->socialUser?->getLastName(),
            'middle_name' => $this->socialUser?->getMiddleName(),
            'current_address' => $this->socialUser?->getCurrentAddress(),
            'description' => $this->socialUser?->getDescription(),
            'phone' => $this->socialUser?->getPhone(),
            'birth_date' => $this->socialUser->getBirthDate(),
            'external_picture' => $this->socialUser->getExternalPicture(),
            'file_name' => $this->socialUser?->getFileName(),
            'file' => $this->socialUser?->file?->getSrc(),
            'login_type' => $this->getLoginType(),
            'status' => $this->getStatus(),
            'merchant' => $merchant
        ]);
    }
}
