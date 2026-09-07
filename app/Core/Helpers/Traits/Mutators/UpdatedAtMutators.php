<?php

namespace App\Core\Helpers\Traits\Mutators;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait UpdatedAtMutators
{
    /**
     * @return Attribute
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $updated_at) => empty($updated_at) ? null : date('d.m.Y H:i:s', strtotime($updated_at)),
            set: fn(string $updated_at) => date('Y-m-d H:i:s', strtotime($updated_at)),
        );
    }
}
