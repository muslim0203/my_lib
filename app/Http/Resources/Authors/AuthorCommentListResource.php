<?php

namespace App\Http\Resources\Authors;

use App\Http\Resources\FileManager\FileViewResource;
use App\Models\Authors\AuthorComments;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AuthorComments
 */
class AuthorCommentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $file = null;

        if (!is_null($this->user->socialUser->file)) {
            $file = new FileViewResource($this->user->socialUser?->file);
        }

        return [
            'id' => $this->getId(),
            'comment' => $this->getComment(),
            'children' => self::collection($this->child),
            'full_name' => $this->user?->socialUser?->getFullName(),
            'file' => $file,
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
        ];
    }
}
