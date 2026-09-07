<?php

namespace App\Core\Filters\Question;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Question\QuestionFilterRequest;
use App\Models\Questions\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuestionSearchFilter
{
    /**
     * @param QuestionFilterRequest $questionFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(QuestionFilterRequest $questionFilterRequest): LengthAwarePaginator
    {
        $query = Question::query();

        $questionFilterRequest->whenFilled('title_uz', function ($value) use ($query) {
            $query->where('title_uz', 'ilike', '%' . $value . '%');
        });

        $questionFilterRequest->whenFilled('title_oz', function ($value) use ($query) {
            $query->where('title_oz', 'ilike', '%' . $value . '%');
        });

        $questionFilterRequest->whenFilled('title_ru', function ($value) use ($query) {
            $query->where('title_ru', 'ilike', '%' . $value . '%');
        });

        $questionFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $questionFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($questionFilterRequest));
    }
}
