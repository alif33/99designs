<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;
    protected $table = 'contests';

    protected $fillable = [
        'title',
        'description',
        'image',
        'posted_by',
        'status'
    ];
}
