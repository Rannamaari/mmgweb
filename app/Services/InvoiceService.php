<?php

namespace App\Services;

use App\Models\Invoice;
use App\Services\InventoryService;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    public function __construct(
        private InventoryService $inventoryService
    ) {}

    public function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $lastInvoice = Invoice::whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastInvoice ? 
            (int) substr($lastInvoice->number, -3) + 1 : 
            1;

        return 'INV-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function calculateTotals(Invoice $invoice): void
    {
        $subtotal = $invoice->items->sum('line_total');
        
        $invoice->update([
            'subtotal' => $subtotal,
            'total' => $subtotal - $invoice->discount + $invoice->tax,
        ]);
    }

    public function finalize(Invoice $invoice): void
    {
        if (!$invoice->isDraft()) {
            throw ValidationException::withMessages([
                'status' => 'Only draft invoices can be finalized.'
            ]);
        }

        // Calculate totals from items
        $this->calculateTotals($invoice);

        // Update status to unpaid
        $invoice->update(['status' => 'unpaid']);

        // Process inventory for parts
        $this->inventoryService->processInvoiceItems($invoice);
    }

    public function markPaid(Invoice $invoice): void
    {
        if (!$invoice->isUnpaid()) {
            throw ValidationException::withMessages([
                'status' => 'Only unpaid invoices can be marked as paid.'
            ]);
        }

        $paidAmount = $invoice->payments->sum('amount');
        
        if ($paidAmount < $invoice->total) {
            throw ValidationException::withMessages([
                'payments' => 'Total payments must equal invoice total to mark as paid.'
            ]);
        }

        $invoice->update(['status' => 'paid']);
    }

    public function cancel(Invoice $invoice): void
    {
        if ($invoice->isCancelled()) {
            throw ValidationException::withMessages([
                'status' => 'Invoice is already cancelled.'
            ]);
        }

        $invoice->update(['status' => 'cancelled']);
        
        // Note: For MVP, we don't restock cancelled invoices
        // This could be added later if needed
    }

    public function createQuickSale(array $items, array $invoiceData, array $paymentData): Invoice
    {
        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber();

        // Create invoice
        $invoice = Invoice::create([
            'number' => $invoiceNumber,
            'date' => now(),
            'customer_id' => $invoiceData['customer_id'] ?? null,
            'motorcycle_id' => $invoiceData['motorcycle_id'] ?? null,
            'subtotal' => 0,
            'discount' => $invoiceData['discount'] ?? 0,
            'tax' => $invoiceData['tax'] ?? 0,
            'total' => 0,
            'status' => 'paid', // Quick sale is immediately paid
            'notes' => $invoiceData['notes'] ?? null,
        ]);

        // Create invoice items
        foreach ($items as $item) {
            $invoice->items()->create([
                'item_type' => $item['product']->type === 'part' ? 'product' : 'service',
                'item_id' => $item['product']->id,
                'description' => $item['product']->name,
                'qty' => $item['qty'],
                'unit_price' => $item['product']->price,
                'line_total' => $item['qty'] * $item['product']->price,
            ]);
        }

        // Calculate totals
        $this->calculateTotals($invoice);

        // Create payment
        $invoice->payments()->create([
            'method' => $paymentData['method'],
            'amount' => $invoice->total,
            'received_at' => now(),
            'reference_no' => $paymentData['reference_no'] ?? null,
            'note' => $paymentData['note'] ?? null,
        ]);

        // Process inventory for parts
        $this->inventoryService->processInvoiceItems($invoice);

        return $invoice;
    }
}