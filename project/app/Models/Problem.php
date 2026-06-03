<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    protected $fillable = [
        'name', 'youtube_links'
    ];

    protected $casts = [
        'youtube_links' => 'array',
    ];
}
