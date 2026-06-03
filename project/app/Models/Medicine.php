<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name', 'stock', 'unit', 'price', 'status', 'items_per_strip'
    ];

    public function transactions()
    {
        return $this->hasMany(MedicineTransaction::class);
    }
}
