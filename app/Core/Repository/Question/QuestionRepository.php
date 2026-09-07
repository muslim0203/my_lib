<?php

namespace App\Core\Repository\Question;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Questions\Question;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class QuestionRepository
{
    public function get(int $id): Builder|Question
    {
        return Question::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param Question $question
     * @return void
     */
    public function save(Question $question): void
    {
        if (!$question->save()) {
            throw new \RuntimeException(__('client.Question save error'));
        }
    }

    public function findAll(): Collection|array
    {
        return Question::query()
            ->where(['enabled' => true])
            ->get();
    }
}
