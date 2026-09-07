<?php

namespace App\Http\Controllers\Web\Questions;

use App\Core\Filters\Question\QuestionSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Question\QuestionRepository;
use App\Http\Requests\Question\QuestionFilterRequest;
use App\Http\Requests\Question\QuestionRequest;
use App\Models\Questions\Question;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QuestionController extends Controller
{
    private QuestionRepository $questionRepository;
    public function __construct(
        QuestionRepository $questionRepository
    )
    {
        $this->questionRepository = $questionRepository;
    }

    public function create(): Application|View|Factory
    {
        return view('pages.question.form',[
            'model' => new Question()
        ]);
    }

    /**
     * @param QuestionRequest $questionRequest
     * @return RedirectResponse
     */
    public function store(QuestionRequest $questionRequest): RedirectResponse
    {
        $model = new Question();
        $model->fill($questionRequest->validated());

        try {
            $this->questionRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('question.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): Application|View|Factory
    {
        $model = $this->findModel($id);

        return view('pages.question.form', compact('model'));
    }

    /**
     * @param QuestionRequest $questionRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(QuestionRequest $questionRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($questionRequest->validated());
        try {
            $this->questionRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('question.filter');
    }

    /**
     * @param QuestionFilterRequest $questionFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(QuestionFilterRequest $questionFilterRequest): View|Factory|Application
    {
        return view('pages.question.filter', [
            'data' => QuestionSearchFilter::search($questionFilterRequest)
        ]);
    }

    /**
     * @param int $id
     * @return Question|Builder
     */
    public function findModel(int $id): Question|Builder
    {
        return $this->questionRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        return redirect()->route('question.filter');
    }

}
