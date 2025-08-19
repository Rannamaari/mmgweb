<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Customer;
use App\Services\InvoiceService;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Debug endpoint (no auth required)
Route::get('/debug', function () {
    return response()->json([
        'auth_check' => auth()->check(),
        'user_id' => auth()->id(),
        'session_id' => session()->getId(),
        'products_count' => App\Models\Product::count(),
        'test_search' => App\Models\Product::where('name', 'ilike', '%engine%')->count(),
    ]);
});

Route::middleware('auth:web')->group(function () {
    // Get customers for POS
    Route::get('/customers', function () {
        return Customer::select('id', 'name', 'phone')->get();
    });

    // Search products for POS
    Route::get('/products/search', function (Request $request) {
        $query = $request->get('q', '');
        $type = $request->get('type', '');
        
        $products = Product::where('is_active', true);
        
        // Apply search filter if query provided
        if (!empty($query)) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'ilike', "%{$query}%")
                  ->orWhere('sku', 'ilike', "%{$query}%");
            });
        }
        
        // Apply type filter if specified
        if (!empty($type) && in_array($type, ['part', 'service'])) {
            $products->where('type', $type);
        }
        
        return $products
            ->select('id', 'name', 'sku', 'price', 'type', 'stock_qty')
            ->orderBy('name')
            ->limit(20)
            ->get();
    });

    // Process POS sale
    Route::post('/pos/sale', function (Request $request, InvoiceService $invoiceService) {
        $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,bank_transfer',
            'customer_id' => 'nullable|exists:customers,id',
            'reference_number' => 'nullable|string|max:255',
        ]);

        try {
            // Prepare items data
            $items = [];
            foreach ($request->cart as $cartItem) {
                $product = Product::find($cartItem['id']);
                if (!$product) {
                    throw new \Exception("Product not found: {$cartItem['id']}");
                }
                
                // Check stock for parts
                if ($product->type === 'part' && $product->stock_qty < $cartItem['qty']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->stock_qty}");
                }

                $items[] = [
                    'product' => $product,
                    'qty' => $cartItem['qty'],
                ];
            }

            // Invoice data
            $invoiceData = [
                'customer_id' => $request->customer_id,
                'motorcycle_id' => null,
                'discount' => 0,
                'tax' => 0,
                'notes' => 'POS Sale',
            ];

            // Payment data
            $paymentData = [
                'method' => $request->payment_method,
                'reference_no' => $request->reference_number,
                'note' => 'POS Payment',
            ];

            // Create the quick sale
            $invoice = $invoiceService->createQuickSale($items, $invoiceData, $paymentData);

            return response()->json([
                'success' => true,
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->number,
                'total' => $invoice->total,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    });
});

// Invoice PDF (accessible without API auth for printing)
Route::get('/invoice/{invoice}/pdf', function (\App\Models\Invoice $invoice) {
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('invoice-pdf', compact('invoice'));
    
    return $pdf->stream("invoice-{$invoice->number}.pdf");
})->middleware('auth:web');