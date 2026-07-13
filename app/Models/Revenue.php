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
        return $this->belongsTo(Patient::class)->withTrashed()->withoutGlobalScope('location')->withDefault([
            'name' => 'Unknown Patient',
            'patient_code' => 'N/A',
            'phone' => 'N/A'
        ]);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class)->withoutGlobalScope('location');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault([
            'name' => 'System'
        ]);
    }
}
