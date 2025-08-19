<?php

namespace App\Services;

use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\Invoice;

class InventoryService
{
    public function decrementStock(Product $product, int $quantity, string $reason = 'sale', $reference = null): void
    {
        // Only track stock for parts, not services
        if ($product->type !== 'part') {
            return;
        }

        // Update product stock
        $product->decrement('stock_qty', $quantity);

        // Log inventory movement
        $this->logMovement($product, -$quantity, $reason, $reference);
    }

    public function incrementStock(Product $product, int $quantity, string $reason = 'purchase', $reference = null): void
    {
        // Only track stock for parts, not services
        if ($product->type !== 'part') {
            return;
        }

        // Update product stock
        $product->increment('stock_qty', $quantity);

        // Log inventory movement
        $this->logMovement($product, $quantity, $reason, $reference);
    }

    public function adjustStock(Product $product, int $newQuantity, string $reason = 'adjustment', $reference = null): void
    {
        // Only track stock for parts, not services
        if ($product->type !== 'part') {
            return;
        }

        $currentStock = $product->stock_qty;
        $change = $newQuantity - $currentStock;

        if ($change !== 0) {
            $product->update(['stock_qty' => $newQuantity]);
            $this->logMovement($product, $change, $reason, $reference);
        }
    }

    public function processInvoiceItems(Invoice $invoice): void
    {
        foreach ($invoice->items as $item) {
            if ($item->product && $item->product->type === 'part') {
                $this->decrementStock(
                    $item->product,
                    (int) $item->qty,
                    'sale',
                    $invoice
                );
            }
        }
    }

    private function logMovement(Product $product, int $change, string $reason, $reference = null): void
    {
        $referenceType = null;
        $referenceId = null;

        if ($reference) {
            $referenceType = get_class($reference);
            $referenceId = $reference->id;
        }

        InventoryMovement::create([
            'product_id' => $product->id,
            'change' => $change,
            'reason' => $reason,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'user_id' => auth()->id(),
            'occurred_at' => now(),
        ]);
    }
}