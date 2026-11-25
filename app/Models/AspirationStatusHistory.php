<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AspirationStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'aspiration_status_histories';

    protected $fillable = [
        'aspiration_id',
        'user_id',
        'previous_status',
        'new_status',
        'notes',
    ];

    /**
     * The aspiration for this status history.
     */
    public function aspiration(): BelongsTo
    {
        return $this->belongsTo(Aspiration::class);
    }

    /**
     * The user who initiated the status change (admin or student).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Badge class for the new status.
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->new_status) {
            'Menunggu' => 'warning text-dark',
            'Diproses' => 'primary',
            'Selesai' => 'success',
            'Ditolak' => 'danger',
            default => 'secondary',
        };
    }
}

