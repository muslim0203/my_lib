<?php

namespace App\Core\Services\Register;

use App\Core\Services\Register\Contracts\Register as RegisterContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    public function __construct(
        protected RegisterContract $registerContract
    )
    {
    }

    /**
     * @param FormRequest $formRequest
     * @param int|null $id
     * @return int
     */
    public function register(FormRequest $formRequest, ?int $id = null): int
    {
        return DB::transaction(function () use ($formRequest, $id) {
            return $this->registerContract->register($formRequest, $id);
        });
    }
}
