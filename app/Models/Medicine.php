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

    protected $appends = ['unit_price'];

    public function getUnitPriceAttribute()
    {
        if ($this->items_per_strip && (int)$this->items_per_strip > 1 && (float)$this->price > 0) {
            return round((float)$this->price / (int)$this->items_per_strip, 2);
        }
        return (float)($this->price ?? 0);
    }

    public function transactions()
    {
        return $this->hasMany(MedicineTransaction::class);
    }
}

