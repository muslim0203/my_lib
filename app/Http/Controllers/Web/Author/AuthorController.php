<?php

namespace App\Http\Controllers\Web\Author;

use App\Core\Filters\Author\AuthorSearchFilter;
use App\Core\Helpers\Response\Success;
use App\Core\Repository\Author\AuthorRepository;
use App\Core\Repository\Enum\AcademicDegreeRepository;
use App\Core\Repository\Enum\AcademicPositionRepository;
use App\Core\Repository\Enum\EducationTypeRepository;
use App\Core\Services\Author\Interfaces\AuthorInterface;
use App\Http\Requests\Authors\AuthorSearchRequest;
use App\Http\Requests\Authors\ConfirmOrCancelRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class AuthorController extends Controller
{
    public function __construct(
        protected AuthorInterface $authorService
    )
    {
    }

    public function filter(AuthorSearchRequest $authorSearchRequest)
    {
        $data = AuthorSearchFilter::search($authorSearchRequest);

        return view('pages.authors.filter', compact('data'));
    }

    /**
     * @param AuthorRepository $authorRepository
     * @param int $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function view(AuthorRepository $authorRepository, int $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $data = $authorRepository->get($id);
        // Academic degree all
        $academicDegrees = app(AcademicDegreeRepository::class)->findAll();
        // Academic position all
        $academicPositions = app(AcademicPositionRepository::class)->findAll();
        // Education types all
        $educationTypes = app(EducationTypeRepository::class)->findAll();


        return view('pages.authors.view', compact('data', 'academicDegrees', 'academicPositions', 'educationTypes'));
    }

    /**
     * @param ConfirmOrCancelRequest $confirmOrCancelRequest
     * @param int $id
     * @return JsonResponse
     */
    public function confirm(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id): JsonResponse
    {
        if (!$this->authorService->confirmOrCancel($confirmOrCancelRequest, $id)) {
            abort(400, __('client.Unknown error'));
        }

        return Success::send('Successful done');
    }

    /**
     * @param ConfirmOrCancelRequest $confirmOrCancelRequest
     * @param int $id
     * @return JsonResponse
     */
    public function cancel(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id): JsonResponse
    {
        if (!$this->authorService->confirmOrCancel($confirmOrCancelRequest, $id, false)) {
            abort(400, __('client.Unknown error'));
        }

        return Success::send('Successful done');
    }
}
