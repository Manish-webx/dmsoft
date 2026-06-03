<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
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
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
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
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }
}
