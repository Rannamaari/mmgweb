<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/', function () {
    return view('index');
});

Route::get('/booking', [App\Http\Controllers\BookingController::class, 'show'])->name('booking.show');
Route::post('/booking', [App\Http\Controllers\BookingController::class, 'store'])->name('booking.store')->middleware('web');
Route::post('/booking-test', [App\Http\Controllers\BookingController::class, 'testStore'])->name('booking.test');

Route::middleware('auth')->group(function () {
    Route::get('/pos', function () {
        return view('pos');
    });
});

// Redirect unauthenticated users to Filament login
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Handle logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/test-login', function (Request $request) {
    $email = $request->get('email', 'admin@mmg.local');
    $password = $request->get('password', 'password');

    $user = User::where('email', $email)->first();

    if (!$user) {
        return response()->json([
            'error' => 'User not found',
            'email' => $email
        ]);
    }

    $authResult = Auth::attempt(['email' => $email, 'password' => $password]);

    return response()->json([
        'user_exists' => !!$user,
        'email' => $email,
        'password_attempt' => $password,
        'auth_result' => $authResult,
        'hash_check' => \Hash::check($password, $user->password),
        'user_id' => $user->id,
        'user_name' => $user->name,
    ]);
});

Route::get('/create-admin', function () {
    $user = User::updateOrCreate(
        ['email' => 'admin@mmg.local'],
        [
            'name' => 'Admin',
            'password' => \Hash::make('password'),
            'email_verified_at' => now(),
        ]
    );

    return response()->json([
        'message' => 'Admin user created/updated',
        'user' => $user,
        'password_test' => \Hash::check('password', $user->password)
    ]);
});

// Test endpoint for API debugging
Route::get('/test-search', function () {
    $products = App\Models\Product::where('name', 'ilike', '%engine%')
        ->select('id', 'name', 'sku', 'price', 'type', 'stock_qty')
        ->take(5)
        ->get();

    return response()->json([
        'products' => $products,
        'count' => $products->count(),
        'user_authenticated' => auth()->check(),
        'user_id' => auth()->id(),
    ]);
})->middleware('auth');

// API endpoints directly in web routes (temporary fix)
Route::middleware('auth')->group(function () {
    Route::get('/web-api/customers', function () {
        return App\Models\Customer::select('id', 'name', 'phone')->get();
    });

    Route::post('/web-api/customers', function (Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'motorcycle_make' => 'required|string|max:255',
            'motorcycle_model' => 'required|string|max:255',
            'motorcycle_plate' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        try {
            // Create customer
            $customer = App\Models\Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
            ]);

            // Create motorcycle and link to customer
            $motorcycle = App\Models\Motorcycle::create([
                'customer_id' => $customer->id,
                'make' => $request->motorcycle_make,
                'model' => $request->motorcycle_model,
                'plate_no' => $request->motorcycle_plate,
                'year' => null, // Can be updated later
                'color' => null, // Can be updated later
                'vin' => null, // Can be updated later
            ]);

            return response()->json([
                'success' => true,
                'customer' => $customer,
                'motorcycle' => $motorcycle,
                'message' => 'Customer and motorcycle created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 400);
        }
    });

    Route::post('/web-api/motorcycles', function (Illuminate\Http\Request $request) {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate_no' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'color' => 'nullable|string|max:255',
        ]);

        try {
            $motorcycle = App\Models\Motorcycle::create([
                'customer_id' => $request->customer_id,
                'make' => $request->make,
                'model' => $request->model,
                'plate_no' => $request->plate_no,
                'year' => $request->year,
                'color' => $request->color,
            ]);

            return response()->json([
                'success' => true,
                'motorcycle' => $motorcycle,
                'message' => 'Motorcycle added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add motorcycle: ' . $e->getMessage()
            ], 400);
        }
    });

    Route::get('/web-api/products/search', function (Illuminate\Http\Request $request) {
        $query = $request->get('q', '');
        $type = $request->get('type', '');

        $products = App\Models\Product::where('is_active', true);

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
    Route::post('/web-api/pos/sale', function (Illuminate\Http\Request $request) {
        $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,bank_transfer',
            'customer_id' => 'nullable|exists:customers,id',
            'reference_number' => 'nullable|string|max:255',
        ]);

        try {
            $invoiceService = app(\App\Services\InvoiceService::class);

            // Prepare items data
            $items = [];
            foreach ($request->cart as $cartItem) {
                $product = App\Models\Product::find($cartItem['id']);
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

            // Auto-link customer's motorcycle if available
            if ($request->customer_id) {
                $customer = App\Models\Customer::find($request->customer_id);
                if ($customer && $customer->motorcycles->count() > 0) {
                    $invoiceData['motorcycle_id'] = $customer->motorcycles->first()->id;
                }
            }

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



// Debug endpoint for authentication status
Route::get('/debug', function () {
    return response()->json([
        'auth_check' => auth()->check(),
        'user_id' => auth()->id(),
        'session_id' => session()->getId(),
        'products_count' => App\Models\Product::count(),
        'test_search' => App\Models\Product::where('name', 'ilike', '%engine%')->count(),
    ]);
});

// Debug search endpoint (no auth required for testing)
Route::get('/debug-search', function (Illuminate\Http\Request $request) {
    $query = $request->get('q', '');
    $type = $request->get('type', '');

    $products = App\Models\Product::where('is_active', true);

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

    $results = $products
        ->select('id', 'name', 'sku', 'price', 'type', 'stock_qty')
        ->orderBy('name')
        ->limit(20)
        ->get();

    return response()->json([
        'success' => true,
        'query' => $query,
        'type' => $type,
        'count' => $results->count(),
        'products' => $results,
        'auth_status' => auth()->check(),
        'user_id' => auth()->id(),
    ]);
});

// Invoice PDF route
Route::get('/invoice/{invoice}/pdf', function (\App\Models\Invoice $invoice) {
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('invoice-pdf', compact('invoice'));

    return $pdf->stream("invoice-{$invoice->number}.pdf");
})->middleware('auth')->name('invoice.pdf');
