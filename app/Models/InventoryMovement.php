<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id',
        'change',
        'reason',
        'reference_type',
        'reference_id',
        'note',
        'user_id',
        'occurred_at',
    ];

    protected $casts = [
        'change' => 'integer',
        'reference_id' => 'integer',
        'user_id' => 'integer',
        'occurred_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the delta (change) with proper formatting
     */
    public function getDeltaAttribute(): string
    {
        $change = $this->change;
        $prefix = $change > 0 ? '+' : '';
        return $prefix . $change;
    }

    /**
     * Get the delta color for display
     */
    public function getDeltaColorAttribute(): string
    {
        return $this->change > 0 ? 'success' : ($this->change < 0 ? 'danger' : 'gray');
    }
}
