<?php

namespace App\Concerns;

use App\Actions\GenerateUniqueSlug;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            $model->slug = app(GenerateUniqueSlug::class)->handle(
                $model::class,
                $model->{static::slugSource()},
            );
        });

        static::updating(function ($model) {
            $column = static::slugSource();

            if ($model->isDirty($column)) {
                $model->slug = app(GenerateUniqueSlug::class)->handle(
                    $model::class,
                    $model->{$column},
                    ignoreId: $model->id,
                );
            }
        });
    }

    protected static function slugSource(): string
    {
        return 'name';
    }
}
