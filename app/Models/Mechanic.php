<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mechanic extends Model
{
    protected $fillable = ['name', 'phone', 'specialization', 'status'];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
