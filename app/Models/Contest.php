<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;
    protected $table = 'contests';

    protected $fillable = [
        'contest_title',
        'contest_description',
        'slug',
        'contest_image',
        'contest_prize',
        'start_date',
        'end_date',
        'posted_by',
        'status'
    ];
}
