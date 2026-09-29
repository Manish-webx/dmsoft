<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'prescription_id', 'medicine_id', 'alternate_medicine_id', 'time', 'when', 'frequency', 'timing', 'duration', 'duration_type', 'quantity', 'instructions', 'medicine_note'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class)->withTrashed();
    }

    public function alternateMedicine()
    {
        return $this->belongsTo(Medicine::class, 'alternate_medicine_id')->withTrashed();
    }

    public function getAlternateMedicinesAttribute()
    {
        if (!$this->alternate_medicine_id) return collect();
        $ids = explode(',', $this->alternate_medicine_id);
        return Medicine::whereIn('id', $ids)->get();
    }
}
