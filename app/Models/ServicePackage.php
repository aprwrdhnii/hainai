<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    protected $fillable = ['name', 'description', 'price', 'duration', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
