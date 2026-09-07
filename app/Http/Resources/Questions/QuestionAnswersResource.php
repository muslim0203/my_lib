<?php

namespace App\Http\Resources\Questions;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Questions\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin QuestionAnswer
 */
class QuestionAnswersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'content' => $this->{LanguageHelper::getContent()},
        ];
    }
}
