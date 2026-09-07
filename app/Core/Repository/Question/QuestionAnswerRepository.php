<?php

namespace App\Core\Repository\Question;

use App\Models\Questions\QuestionAnswer;
use Illuminate\Contracts\Database\Eloquent\Builder;

class QuestionAnswerRepository
{
    public function get(int $id): Builder|QuestionAnswer
    {
        return QuestionAnswer::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param QuestionAnswer $questionAnswer
     * @return void
     */
    public function save(QuestionAnswer $questionAnswer): void
    {
        if (!$questionAnswer->save()) {
            throw new \RuntimeException(__('client.Question Answer save error'));
        }
    }
}
