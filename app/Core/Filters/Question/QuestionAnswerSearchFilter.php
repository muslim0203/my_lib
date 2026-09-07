<?php

namespace App\Core\Filters\Question;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Question\QuestionAnswerFilterRequest;
use App\Models\Questions\QuestionAnswer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuestionAnswerSearchFilter
{
    /**
     * @param QuestionAnswerFilterRequest $questionAnswerFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(QuestionAnswerFilterRequest $questionAnswerFilterRequest): LengthAwarePaginator
    {
        $query = QuestionAnswer::query();

        $questionAnswerFilterRequest->whenFilled('content_uz', function ($value) use ($query) {
            $query->where('content_uz', 'ilike', '%' . $value . '%');
        });

        $questionAnswerFilterRequest->whenFilled('content_oz', function ($value) use ($query) {
            $query->where('content_oz', 'ilike', '%' . $value . '%');
        });

        $questionAnswerFilterRequest->whenFilled('content_ru', function ($value) use ($query) {
            $query->where('content_ru', 'ilike', '%' . $value . '%');
        });

        $questionAnswerFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $questionAnswerFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($questionAnswerFilterRequest));
    }
}
