<?php

namespace App\Core\Helpers\Traits\Mutators;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait CreatedAtMutators
{
    /**
     * @return Attribute
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $created_at) => date('d.m.Y H:i:s', strtotime($created_at)),
            set: fn(string $created_at) => date('Y-m-d H:i:s', strtotime($created_at)),
        );
    }
}
