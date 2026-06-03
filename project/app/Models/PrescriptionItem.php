<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'prescription_id', 'medicine_id', 'alternate_medicine_id', 'timing', 'duration', 'quantity', 'instructions', 'medicine_note'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function alternateMedicine()
    {
        return $this->belongsTo(Medicine::class, 'alternate_medicine_id');
    }
}
