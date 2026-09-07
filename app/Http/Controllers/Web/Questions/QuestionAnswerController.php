<?php

namespace App\Http\Controllers\Web\Questions;

use App\Core\Filters\Question\QuestionAnswerSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Question\QuestionAnswerRepository;
use App\Core\Repository\Question\QuestionRepository;
use App\Http\Requests\Question\QuestionAnswerFilterRequest;
use App\Http\Requests\Question\QuestionAnswerRequest;
use App\Models\Questions\Question;
use App\Models\Questions\QuestionAnswer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QuestionAnswerController extends Controller
{
    private QuestionAnswerRepository $questionAnswerRepository;
    private QuestionRepository $questionRepository;
    public function __construct(
        QuestionAnswerRepository $questionAnswerRepository,
        QuestionRepository $questionRepository
    )
    {
        $this->questionAnswerRepository = $questionAnswerRepository;
        $this->questionRepository = $questionRepository;
    }

    public function create(): Application|View|Factory
    {
        return view('pages.question-answer.form',[
            'model' => new QuestionAnswer(),
            'questions' => $this->questionRepository->findAll()
        ]);
    }

    /**
     * @param QuestionAnswerRequest $questionAnswerRequest
     * @return RedirectResponse
     */
    public function store(QuestionAnswerRequest $questionAnswerRequest): RedirectResponse
    {
        $model = new QuestionAnswer();
        $model->fill($questionAnswerRequest->validated());
        try {
            $this->questionAnswerRepository->save($model);
        } catch (\RuntimeException $runtimeException) {
            return back()->with('exception', $runtimeException->getMessage());
        }

        Session::flash('success', __('client.Successful saved'));

        return redirect()->route('question-answer.filter');
    }

    /**
     * @param int $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function update(int $id): Application|View|Factory
    {
        $model = $this->findModel($id);

        return view('pages.question-answer.form', compact('model'),[
            'questions' => $this->questionRepository->findAll()
        ]);
    }

    /**
     * @param QuestionAnswerRequest $questionAnswerRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(QuestionAnswerRequest $questionAnswerRequest, int $id): RedirectResponse
    {
        $model = $this->findModel($id);

        $model->fill($questionAnswerRequest->validated());
        try {
            $this->questionAnswerRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('question-answer.filter');
    }

    /**
     * @param QuestionAnswerFilterRequest $questionAnswerFilterRequest
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function filter(QuestionAnswerFilterRequest $questionAnswerFilterRequest): View|Factory|Application
    {
        return view('pages.question-answer.filter', [
            'data' => QuestionAnswerSearchFilter::search($questionAnswerFilterRequest)
        ]);
    }

    public function findModel(int $id): QuestionAnswer|Builder
    {

        return $this->questionAnswerRepository->get($id);
    }

    public function destroy(int $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $model = $this->findModel($id);

        if ($model->delete() !== true) {
            return Success::error('Unknown error')->setStatusCode(400);
        }

        Session::flash('success', __('client.Successful removed'));

        return redirect()->route('question-answer.filter');
    }

}
