<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionTemplateItem extends Model
{
    protected $fillable = [
        'prescription_template_id',
        'medicine_id',
        'alternate_medicine_id',
        'quantity',
        'time',
        'when',
        'frequency',
        'timing',
        'duration',
        'duration_type',
        'instructions',
        'medicine_note',
    ];

    public function template()
    {
        return $this->belongsTo(PrescriptionTemplate::class, 'prescription_template_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    public function alternateMedicine()
    {
        return $this->belongsTo(Medicine::class, 'alternate_medicine_id');
    }
}
