<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BalanceTransaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'description',
        'trade_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship back to Trade.
     */
    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class, 'trade_id');
    }
}
