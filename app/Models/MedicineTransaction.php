<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineTransaction extends Model
{
    protected $fillable = [
        'medicine_id', 'type', 'quantity', 'reference_type', 'reference_id', 'created_by'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
