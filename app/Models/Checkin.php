<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkin extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'location_id',
        'selfie_photo',
        'latitude',
        'longitude',
        'checkout_time',
        'checkout_latitude',
        'checkout_longitude',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'checkout_latitude' => 'decimal:8',
            'checkout_longitude' => 'decimal:8',
            'checkout_time' => 'datetime',
        ];
    }
    
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->checkout_time === null;
    }
    
    public function canCheckOut(): bool
    {
        return $this->isActive();
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
