<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\FileManager\FileViewResource;
use App\Models\Products\ProductComment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductComment
 */
class ProductCommentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $file = null;

        if (!is_null($this->product->author->socialUser->file)) {
            $file = new FileViewResource($this->product->author->socialUser->file);
        }

        return [
            'id' => $this->getId(),
            'comment' => $this->getComment(),
            'children' => self::collection($this->child),
            'full_name' => $this->author->socialUser?->getFullName(),
            'file' => $file,
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
        ];
    }
}
