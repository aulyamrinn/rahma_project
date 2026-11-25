<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aspiration extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'title',
        'location',
        'description',
        'photo',
        'status',
    ];

    /**
     * Boot function to automatically generate ticket numbers if missing.
     */
    protected static function booted()
    {
        static::creating(function ($aspiration) {
            if (empty($aspiration->ticket_number)) {
                $dateCode = now()->format('Ymd');
                $randomDigits = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
                $aspiration->ticket_number = 'SC-' . $dateCode . '-' . $randomDigits;
            }
        });
    }

    /**
     * User who submitted the aspiration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category of the facility.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Feedback given by admins.
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class)->latest();
    }

    /**
     * History of status changes.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(AspirationStatusHistory::class)->latest();
    }

    /**
     * Bootstrap badge color class based on status.
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'Menunggu' => 'warning text-dark',
            'Diproses' => 'primary',
            'Selesai' => 'success',
            'Ditolak' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Icon for current status.
     */
    public function getStatusIcon(): string
    {
        return match ($this->status) {
            'Menunggu' => 'bi-hourglass-split',
            'Diproses' => 'bi-gear-wide-connected',
            'Selesai' => 'bi-check-circle-fill',
            'Ditolak' => 'bi-x-circle-fill',
            default => 'bi-question-circle',
        };
    }

    /**
     * Get accessible photo URL.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return null;
        }

        return route('media.photo', ['path' => $this->photo]);
    }
}

