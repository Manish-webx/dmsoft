<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasLocation;

class Medicine extends Model
{
    use HasLocation;
    use SoftDeletes;
    protected $fillable = [
        'name', 'brand', 'stock', 'unit', 'price', 'status', 'items_per_strip'
    ];

    public function transactions()
    {
        return $this->hasMany(MedicineTransaction::class);
    }
}
