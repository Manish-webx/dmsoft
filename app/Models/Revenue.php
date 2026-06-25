<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLocation;

class Revenue extends Model
{
    use HasLocation;

    protected $fillable = [
        'patient_id', 'prescription_id', 'subtotal', 'discount_type', 
        'discount_value', 'final_amount', 'received_amount', 'due_amount', 
        'payment_mode', 'payment_status', 'notes', 'created_by'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
