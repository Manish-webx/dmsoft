<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasLocation;

class Prescription extends Model
{
    use SoftDeletes, HasLocation;
    protected $fillable = [
        'patient_id', 'doctor_id', 'date', 'next_followup_date', 'notes',
        'is_edited', 'edited_by', 'edited_at', 'problem_id', 'youtube_links', 'hopi'
    ];

    protected $casts = [
        'date' => 'date',
        'next_followup_date' => 'date',
        'youtube_links' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed()->withoutGlobalScope('location')->withDefault([
            'name' => 'Unknown Patient',
            'patient_code' => 'N/A',
            'phone' => 'N/A'
        ]);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')->withDefault([
            'name' => 'Unknown Doctor'
        ]);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function revenue()
    {
        return $this->hasOne(Revenue::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by')->withDefault([
            'name' => 'Unknown Editor'
        ]);
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class)->withDefault([
            'name' => 'Unknown Problem'
        ]);
    }
}
