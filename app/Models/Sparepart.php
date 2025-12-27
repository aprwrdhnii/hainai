<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sparepart extends Model
{
    protected $fillable = ['code', 'name', 'category', 'image', 'stock', 'buy_price', 'sell_price', 'min_stock'];

    public function serviceDetails(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }
}
