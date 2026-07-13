<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasLocation;

class Appointment extends Model
{
    use SoftDeletes, HasLocation;
    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_date', 'status', 'notes', 'followup_date'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'followup_date' => 'date',
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
}
