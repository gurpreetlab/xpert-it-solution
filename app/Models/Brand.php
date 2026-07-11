<?php

namespace App\Models;

use App\Concerns\HasSlug;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable(["name", "logo", "description"])]
class Brand extends Model
{
    use SoftDeletes, HasSlug;

    protected $casts = [
        'products_count' => 'integer',
    ];

    /**
     * Scope a query to search for categories by name.
     *
     * @param Builder $query
     * @param string|null $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                $words = preg_split('/\s+/', trim($this->name));

                if (count($words) >= 2) {
                    return Str::upper(
                        Str::substr($words[0], 0, 1) .
                        Str::substr($words[1], 0, 1)
                    );
                }

                return Str::upper(Str::substr($this->name, 0, 2));
            },
        );
    }
}
