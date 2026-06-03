<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'patient_code', 'name', 'phone', 'gender', 'dob', 'address', 'notes'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->patient_code)) {
                $datePrefix = now()->format('Ymd');
                $lastPatient = self::where('patient_code', 'like', $datePrefix . '-%')
                                   ->orderBy('patient_code', 'desc')
                                   ->first();

                if ($lastPatient) {
                    $lastSequence = (int) substr($lastPatient->patient_code, -3);
                    $newSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newSequence = '001';
                }

                $patient->patient_code = $datePrefix . '-' . $newSequence;
            }
        });
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
