<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lat_allowed',
        'lng_allowed',
        'radius_allowed',
    ];

    protected function casts(): array
    {
        return [
            'lat_allowed' => 'decimal:8',
            'lng_allowed' => 'decimal:8',
        ];
    }

    /**
     * Google Maps widget için computed location attribute
     */
    public function getLocationAttribute(): array
    {
        return [
            'lat' => (float) $this->lat_allowed,
            'lng' => (float) $this->lng_allowed,
        ];
    }

    /**
     * Filament Google Maps için computed location setter
     */
    public function setLocationAttribute($value): void
    {
        if (is_array($value) && isset($value['lat']) && isset($value['lng'])) {
            $this->attributes['lat_allowed'] = $value['lat'];
            $this->attributes['lng_allowed'] = $value['lng'];
        }
    }

    // Çalışanlarla many-to-many ilişki
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_location')
                    ->withTimestamps();
    }

    // Bu lokasyondaki vardiyalar
    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    // Bu lokasyondaki check-in'ler
    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    /**
     * İki koordinat arasındaki mesafeyi hesapla (metre cinsinden)
     * Çalışanın anlık konumu ile lokasyonun tanımlı merkez noktası arasında
     */
    public function distanceFrom($latitude, $longitude): float
    {
        $earthRadius = 6371000; // metre cinsinden dünya yarıçapı

        $latFrom = deg2rad($this->lat_allowed);
        $lonFrom = deg2rad($this->lng_allowed);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta/2) * sin($latDelta/2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta/2) * sin($lonDelta/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /**
     * Çalışanın check-in yapabileceği mesafede olup olmadığını kontrol et
     */
    public function isWithinAllowedRadius($latitude, $longitude): bool
    {
        $distance = $this->distanceFrom($latitude, $longitude);
        return $distance <= $this->radius_allowed;
    }

    /**
     * Check-in doğrulaması yap
     */
    public function validateCheckin($employee, $latitude, $longitude): array
    {
        // Mesafe kontrolü
        $distance = $this->distanceFrom($latitude, $longitude);
        
        if (!$this->isWithinAllowedRadius($latitude, $longitude)) {
            return [
                'valid' => false,
                'message' => "Lütfen belirtilen lokasyonun {$this->radius_allowed} metre içine dahil olun ve doğrulamayı tekrar deneyin. Mevcut mesafeniz: " . round($distance) . "m",
                'distance' => $distance
            ];
        }

        return [
            'valid' => true,
            'message' => 'Konum doğrulandı! Check-in yapabilirsiniz.',
            'distance' => $distance
        ];
    }

}
