<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUUIDField
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($model): void {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
