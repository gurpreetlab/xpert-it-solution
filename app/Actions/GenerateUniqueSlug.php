<?php

namespace App\Actions;

use Illuminate\Support\Str;

class GenerateUniqueSlug
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    /**
     * Generate a unique slug
     */
    public function handle(
        string $model,
        string $value,
        string $slugColumn = 'slug',
        ?int $ignoreId = null,
    ): string {
        $slug = Str::slug($value);

        // Fallback if slug is empty
        if (blank($slug)) {
            $slug = Str::random(8);
        }

        $original = $slug;
        $count = 1;

        while (
            $model::query()
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->where($slugColumn, $slug)
                ->exists()
        ) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}
