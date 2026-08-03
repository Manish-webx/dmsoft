<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionTemplate extends Model
{
    protected $fillable = [
        'name',
        'doctor_id',
        'problem_id',
        'notes',
        'hopi',
        'youtube_links',
    ];

    protected $casts = [
        'youtube_links' => 'array',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')->withDefault([
            'name' => 'Unknown Doctor'
        ]);
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class)->withDefault([
            'name' => 'Unknown Problem'
        ]);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionTemplateItem::class);
    }
}
