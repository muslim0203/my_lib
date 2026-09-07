<?php

namespace App\Core\Repository\Step;

use App\Models\Steps\ProcessStep;
use Illuminate\Database\Eloquent\Builder;

class ProcessStepRepository
{
    /**
     * @param string $code_name
     * @return Builder|ProcessStep
     */
    public function getByCodeName(string $code_name): Builder|ProcessStep
    {
        return ProcessStep::query()
            ->where('code_name', $code_name)
            ->where('enabled', true)
            ->firstOrFail();
    }
}
