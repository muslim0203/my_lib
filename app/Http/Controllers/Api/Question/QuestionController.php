<?php

namespace App\Http\Controllers\Api\Question;

use App\Core\Repository\Question\QuestionRepository;
use App\Http\Resources\Questions\QuestionsResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class QuestionController extends Controller
{
    public function list(QuestionRepository $questionRepository): AnonymousResourceCollection
    {
        return QuestionsResource::collection($questionRepository->findAll());
    }
}
