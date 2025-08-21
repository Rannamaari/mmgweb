<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'type',
        'price',
        'cost',
        'stock_qty',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock_qty' => 'integer',
        'is_active' => 'boolean',
    ];

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'item_id');
    }

    public function scopeParts($query)
    {
        return $query->where('type', 'part');
    }

    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }

    public function scopeActive($query)
    {
        return $query->whereRaw('is_active = true');
    }
}
