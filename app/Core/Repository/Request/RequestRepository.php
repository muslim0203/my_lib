<?php

namespace App\Core\Repository\Request;

use App\Core\Enums\Requests\RequestStatusEnum;
use App\Core\Enums\Requests\RequestTypeEnum;
use App\Models\Request\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Json;

class RequestRepository
{
    /**
     * @param Request $request
     * @param array $options
     * @return void
     */
    public function save(Request $request, array $options = []): void
    {
        if (!$request->save($options)) {
            throw new \RuntimeException(__('client.Save error request'));
        }
    }

    /**
     * @param int $id
     * @param array $request_type_ids
     * @return Request|Builder|null
     */
    public function findByAuthor(int $id, array $request_type_ids = []): Request|Builder|null
    {
        return Request::query()
            ->where('author_id', $id)
            ->whereIn('request_type_id', $request_type_ids)
            ->whereNot('status', RequestStatusEnum::_REJECTED->value)
            ->first();
    }

    /**
     * @param string $model
     * @return Builder|Request|null
     */
    public function findByModel(string $model): Builder|Request|null
    {
        return Request::query()
            ->where('model', $model)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * @param int $id
     * @return Builder|Request
     */
    public function get(int $id): Request|Builder
    {
        return Request::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getData(int $id): mixed
    {
        $data = Request::query()->where('id', $id)->firstOrFail();
        return Json::decode($data['data']);
    }
}
