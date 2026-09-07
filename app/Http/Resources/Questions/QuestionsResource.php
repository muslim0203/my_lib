<?php

namespace App\Http\Resources\Questions;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Questions\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Question
 */
class QuestionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->{LanguageHelper::getTitle()},
            'sort' => $this->sort,
            'questionAnswer' => new QuestionAnswersResource($this->questionAnswer),
        ];
    }
}
