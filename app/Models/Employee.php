<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
class Employee extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Çalışabileceği lokasyonlar (many-to-many)
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'employee_location')
                    ->withTimestamps();
    }

    /**
     * Çalışanın belirli bir lokasyonda çalışma yetkisi var mı?
     */
    public function canWorkAt(Location $location): bool
    {
        return $this->locations()->where('location_id', $location->id)->exists();
    }

    /**
     * Çalışanın bugünkü vardiyalarını getir
     */
    public function todayShifts()
    {
        return $this->shifts()
                    ->where('work_date', today())
                    ->with('location');
    }

    /**
     * Çalışanın bugünkü çalışma lokasyonunu getir
     */
    public function getTodayLocation()
    {
        $todayShift = $this->todayShifts()->first();
        return $todayShift ? $todayShift->location : null;
    }

    /**
     * Son check-in'i getir
     */
    public function lastCheckin()
    {
        return $this->hasOne(Checkin::class)->latest();
    }

    /**
     * Check-in sayısı için accessor
     */
    public function getCheckinsCountAttribute()
    {
        return $this->checkins()->count();
    }

    /**
     * Employee panel erişim kontrolü
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Tüm employee kullanıcıları erişebilir
    }
}
