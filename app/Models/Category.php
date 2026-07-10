<?php

namespace App\Models;

use App\Actions\GenerateUniqueSlug;
use App\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(["name"])]
class Category extends Model
{
    use HasFactory, SoftDeletes, HasSlug;
}
