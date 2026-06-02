<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Trade extends Model
{
    protected $fillable = [
        'pair',
        'type',
        'entry_price',
        'stop_loss',
        'take_profit',
        'lot_size',
        'profit_loss',
        'status',
    ];

    protected $casts = [
        'entry_price' => 'decimal:5',
        'stop_loss' => 'decimal:5',
        'take_profit' => 'decimal:5',
        'lot_size' => 'decimal:2',
        'profit_loss' => 'decimal:2',
    ];

    /**
     * Relationship to BalanceTransaction.
     */
    public function balanceTransaction(): HasOne
    {
        return $this->hasOne(BalanceTransaction::class, 'trade_id');
    }

    /**
     * Get the Risk-to-Reward Ratio (RRR).
     * For BUY: (Take Profit - Entry) / (Entry - Stop Loss)
     * For SELL: (Entry - Take Profit) / (Stop Loss - Entry)
     */
    public function getRiskRewardRatioAttribute(): float
    {
        $entry = (float) $this->entry_price;
        $sl = (float) $this->stop_loss;
        $tp = (float) $this->take_profit;

        if ($this->type === 'buy') {
            $risk = $entry - $sl;
            $reward = $tp - $entry;
        } else {
            $risk = $sl - $entry;
            $reward = $entry - $tp;
        }

        // Avoid division by zero or invalid negative risk values
        if ($risk <= 0) {
            return 0.00;
        }

        return round($reward / $risk, 2);
    }

    /**
     * Boot the model.
     * Automatically manage BalanceTransaction when Trade is saved or deleted.
     */
    protected static function booted()
    {
        static::saved(function (self $trade) {
            if ($trade->status === 'closed') {
                // Upsert balance transaction for the closed trade
                $trade->balanceTransaction()->updateOrCreate(
                    ['trade_id' => $trade->id],
                    [
                        'type' => 'trade_result',
                        'amount' => $trade->profit_loss,
                        'description' => "Hasil Trade: " . strtoupper($trade->type) . " " . strtoupper($trade->pair) . " (" . number_format($trade->lot_size, 2) . " Lot)",
                    ]
                );
            } else {
                // If trade is set back to open, delete the associated balance transaction
                $trade->balanceTransaction()->delete();
            }
        });
    }
}
