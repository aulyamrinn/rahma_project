<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    /**
     * Explicit table name as required by UKK specification.
     */
    protected $table = 'feedback';

    protected $fillable = [
        'aspiration_id',
        'user_id',
        'response',
    ];

    /**
     * The aspiration being responded to.
     */
    public function aspiration(): BelongsTo
    {
        return $this->belongsTo(Aspiration::class);
    }

    /**
     * The admin user who wrote this feedback.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

