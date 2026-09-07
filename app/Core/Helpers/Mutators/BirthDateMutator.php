<?php

namespace App\Core\Helpers\Mutators;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait BirthDateMutator
{
    /**
     * @return Attribute
     */
    protected function birthDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => !empty($value) ? date('d.m.Y', strtotime($value)) : '',
            set: fn($value) => !empty($value) ? date('Y-m-d', strtotime($value)) : ''
        );
    }
}
