<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelLike\Traits\Likeable;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Story extends Model
{
    use HasFactory, Likeable, Favoriteable;
    protected $table = 'stories';

    protected $fillable = [
        'title',
        'details',
        'summary',
        'tags',
        'slug',
        'story_image',
        'adult',
        'stories_status',
        'contest_id',
        'category_id',
        'added_by',
    ];
}
