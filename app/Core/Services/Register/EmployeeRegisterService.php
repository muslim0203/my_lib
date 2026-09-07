<?php

namespace App\Core\Services\Register;

use App\Core\Services\Register\Contracts\Register;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRegisterService implements Register
{
    public function register(FormRequest $formRequest, ?int $id = null): int
    {
        return 0;
    }
}
