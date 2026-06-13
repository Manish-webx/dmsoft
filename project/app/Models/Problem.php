<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problem extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'youtube_links'
    ];

    protected $casts = [
        'youtube_links' => 'array',
    ];
}
