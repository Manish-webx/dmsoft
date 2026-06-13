<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'stock', 'unit', 'price', 'status', 'items_per_strip'
    ];

    public function transactions()
    {
        return $this->hasMany(MedicineTransaction::class);
    }
}
