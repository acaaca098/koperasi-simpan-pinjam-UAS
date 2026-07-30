<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ---- Relasi ----

    /**
     * Data keanggotaan koperasi milik user ini (hanya ada kalau role = anggota).
     * Sesuai ERD: USERS "1" --- "0..1" ANGGOTA.
     */
    public function anggota(): HasOne
    {
        return $this->hasOne(Anggota::class);
    }

    /**
     * Notifikasi in-app milik user ini (dikirim oleh NotificationService).
     */
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // ---- Role helper (sesuai class diagram: isAnggota, isPengurus, isKetua) ----

    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    public function isPengurus(): bool
    {
        return $this->role === 'pengurus';
    }

    public function isKetua(): bool
    {
        return $this->role === 'ketua';
    }

    // ---- Filament Access Control ----

    public function canAccessPanel(Panel $panel): bool
    {
        // Sesuai Component Diagram: Anggota memakai Web Routes biasa,
        // hanya Pengurus & Ketua yang punya akses ke Filament Panel.
        return in_array($this->role, ['pengurus', 'ketua']);
    }
}