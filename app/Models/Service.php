<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Service extends Model
{
    protected $fillable = [
        'service_number', 'vehicle_id', 'mechanic_id', 'service_date', 'service_time',
        'complaint', 'diagnosis', 'status', 'labor_cost', 'total_parts', 'total'
    ];

    protected $casts = [
        'service_date' => 'date',
        'service_time' => 'datetime:H:i',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(Mechanic::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public static function generateServiceNumber(): string
    {
        $prefix = 'SRV-' . date('Ymd');
        $last = self::where('service_number', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        $number = $last ? (int)substr($last->service_number, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'service_details')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }
}
