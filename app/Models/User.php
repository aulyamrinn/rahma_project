<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        'role',
        'nisn',
        'kelas',
        'phone',
        'avatar',
        'password',
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

    /**
     * Check if user is administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is administrator.
     */
    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }


    /**
     * Check if user is student.
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Aspirations created by the user (student).
     */
    public function aspirations(): HasMany
    {
        return $this->hasMany(Aspiration::class);
    }

    /**
     * Feedbacks given by the user (admin).
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Status histories logged by the user.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(AspirationStatusHistory::class);
    }
}
