<?php

namespace App\Core\Services\Links;

use App\Core\Repository\LInks\LinkProductGenreRepository;
use App\Models\Links\LinkProductGenre;
use Illuminate\Foundation\Http\FormRequest;

class LinkProductGenreService
{
    public function __construct(
        protected LinkProductGenreRepository $linkProductGenreRepository
    )
    {
    }

    /**
     * @param array|FormRequest $formRequest
     * @param int $product_id
     * @return void
     */
    public function save(array|FormRequest $formRequest, int $product_id): void
    {
        if (is_array($formRequest) && !empty($formRequest)) {
            foreach ($formRequest as $item) {
                $model = new LinkProductGenre();
                $model->setGenreId($item);
                $model->setProductId($product_id);
                $this->linkProductGenreRepository->save($model);
            }
        }

        if ($formRequest instanceof FormRequest) {
            $model = new LinkProductGenre();
            $model->fill($formRequest->validated());
            $model->setProductId($product_id);
            $this->linkProductGenreRepository->save($model);
        }
    }
}
