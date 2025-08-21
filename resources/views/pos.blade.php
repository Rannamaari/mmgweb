<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS System - Micro Moto Garage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'red-primary': '#DC2626',
                        'red-secondary': '#EF4444',
                        'black-primary': '#0F0F0F',
                        'black-secondary': '#1F1F1F',
                        'gray-dark': '#2D2D2D',
                    }
                }
            }
        }
    </script>
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.1);
        }

        .search-result-item:hover {
            background: linear-gradient(90deg, rgba(220, 38, 38, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-black-primary text-white min-h-screen" x-data="posApp()">
    <!-- Header -->
    <header class="bg-black-secondary border-b border-red-primary/20 sticky top-0 z-40">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="text-red-primary text-3xl">🏍️</div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">MMG POS SYSTEM</h1>
                        <p class="text-gray-400 text-sm">Quick Sale & Service Manager</p>
                    </div>
                </div>

                <!-- Status & Navigation -->
                <div class="flex items-center space-x-4">
                    <div class="hidden md:block text-sm text-gray-400">
                        <span x-text="new Date().toLocaleTimeString('en-US', {timeZone: 'Indian/Maldives'})"></span>
                        <span class="text-xs text-gray-500 ml-1">MVT</span>
                    </div>

                    <!-- Auth Status -->
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full" :class="isAuthenticated ? 'bg-green-500' : 'bg-red-500'">
                        </div>
                        <span class="text-xs" :class="isAuthenticated ? 'text-green-400' : 'text-red-400'">
                            <span x-text="isAuthenticated ? 'Authenticated' : 'Not Logged In'"></span>
                        </span>
                    </div>

                    <a href="{{ url('/') }}"
                        class="bg-gray-dark hover:bg-gray-600 px-4 py-2 rounded-lg transition duration-200 text-sm">
                        Home
                    </a>
                    <a href="{{ url('/admin') }}"
                        class="bg-red-primary hover:bg-red-secondary px-4 py-2 rounded-lg transition duration-200 text-sm">
                        Admin
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Product/Service Search -->
            <div class="xl:col-span-2">
                <div class="bg-black-secondary rounded-lg border border-gray-dark p-6 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-white">
                            🔍 Search Products & Services
                        </h2>
                        <div class="text-sm text-gray-400" x-show="searchResults.length > 0">
                            <span x-text="searchResults.length"></span> results found
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="relative mb-4">
                        <input type="text" x-model="searchQuery" @input="searchProducts"
                            @keydown.enter.prevent="quickAdd" placeholder="Search by name, SKU, or service..."
                            class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary transition duration-200 pr-10">
                        <div class="absolute right-3 top-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Quick Filter Buttons -->
                    <div class="flex space-x-2 mb-4">
                        <button @click="filterType = 'all'; searchProducts()"
                            :class="filterType === 'all' ? 'bg-red-primary' : 'bg-gray-dark hover:bg-gray-600'"
                            class="px-3 py-2 rounded-lg text-sm transition duration-200">
                            All Items
                        </button>
                        <button @click="filterType = 'part'; searchProducts()"
                            :class="filterType === 'part' ? 'bg-red-primary' : 'bg-gray-dark hover:bg-gray-600'"
                            class="px-3 py-2 rounded-lg text-sm transition duration-200">
                            🔧 Parts
                        </button>
                        <button @click="filterType = 'service'; searchProducts()"
                            :class="filterType === 'service' ? 'bg-red-primary' : 'bg-gray-dark hover:bg-gray-600'"
                            class="px-3 py-2 rounded-lg text-sm transition duration-200">
                            🛠️ Services
                        </button>

                    </div>

                    <!-- Search Results -->
                    <div x-show="searchResults.length > 0"
                        class="max-h-96 overflow-y-auto border border-gray-600 rounded-lg">
                        <template x-for="product in searchResults" :key="product.id">
                            <div class="p-4 border-b border-gray-700 search-result-item cursor-pointer transition duration-200"
                                @click="addToCart(product)" @keydown.enter="addToCart(product)" tabindex="0">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="font-medium text-white" x-text="product.name"></span>
                                            <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                                :class="product.type === 'part' ? 'bg-green-900 text-green-300' : 'bg-blue-900 text-blue-300'"
                                                x-text="product.type.toUpperCase()"></span>
                                        </div>
                                        <div class="text-sm text-gray-400 space-x-4">
                                            <span x-show="product.sku" x-text="'SKU: ' + product.sku"></span>
                                            <span x-show="product.type === 'part' && product.stock_qty !== undefined"
                                                :class="product.stock_qty < 5 ? 'text-red-400' : 'text-gray-400'"
                                                x-text="'Stock: ' + product.stock_qty"></span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xl font-bold text-red-primary">ރ<span
                                                x-text="parseFloat(product.price).toFixed(2)"></span></span>
                                        <div class="text-xs text-gray-400">Click to add</div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- No Results Message -->
                    <div x-show="searchQuery.length >= 2 && searchResults.length === 0"
                        class="text-center py-8 text-gray-400">
                        <div class="text-4xl mb-2">🔍</div>
                        <p>No products or services found for "<span x-text="searchQuery"></span>"</p>
                        <p class="text-sm mt-1">Try searching with different keywords</p>
                    </div>
                </div>

                <!-- Shopping Cart -->
                <div class="bg-black-secondary rounded-lg border border-gray-dark p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-white">
                            🛒 Shopping Cart
                        </h2>
                        <div class="text-sm text-gray-400" x-show="cart.length > 0">
                            <span x-text="cart.length"></span> items
                        </div>
                    </div>

                    <!-- Empty Cart State -->
                    <div x-show="cart.length === 0" class="text-center py-12 text-gray-400">
                        <div class="text-6xl mb-4">🛒</div>
                        <p class="text-lg">Cart is empty</p>
                        <p class="text-sm">Search and add products or services above</p>
                    </div>

                    <!-- Cart Items -->
                    <div x-show="cart.length > 0" class="space-y-3">
                        <template x-for="(item, index) in cart" :key="item.id + '_' + index">
                            <div class="bg-gray-dark p-4 rounded-lg border border-gray-600 card-hover">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="font-medium text-white" x-text="item.name"></span>
                                            <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                                :class="item.type === 'part' ? 'bg-green-900 text-green-300' : 'bg-blue-900 text-blue-300'"
                                                x-text="item.type.toUpperCase()"></span>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="flex items-center bg-black-primary rounded-lg border border-gray-600">
                                                <button @click="decreaseQty(index)"
                                                    class="px-3 py-2 text-gray-400 hover:text-white transition duration-200">
                                                    −
                                                </button>
                                                <input type="number" x-model.number="item.qty" @input="updateTotal()"
                                                    min="1"
                                                    class="w-16 px-2 py-2 bg-transparent text-center text-white focus:outline-none">
                                                <button @click="increaseQty(index)"
                                                    class="px-3 py-2 text-gray-400 hover:text-white transition duration-200">
                                                    +
                                                </button>
                                            </div>
                                            <span class="text-sm text-gray-400">
                                                × ރ<span x-text="parseFloat(item.price).toFixed(2)"></span>
                                            </span>
                                        </div>

                                        <!-- Price Editing -->
                                        <div class="flex items-center space-x-2 mt-2">
                                            <button @click="startEditPrice(index)" x-show="!item.editingPrice"
                                                class="text-xs text-blue-400 hover:text-blue-300 transition duration-200">
                                                ✏️ Edit Price
                                            </button>
                                            <div x-show="item.editingPrice" class="flex items-center space-x-2">
                                                <span class="text-xs text-gray-400">ރ</span>
                                                <input type="number" x-model.number="item.newPrice"
                                                    @keydown.enter="savePrice(index)"
                                                    @keydown.escape="cancelEditPrice(index)" min="0" step="0.01"
                                                    class="w-20 px-2 py-1 bg-black-primary border border-gray-600 rounded text-xs text-white focus:outline-none focus:border-blue-400">
                                                <button @click="savePrice(index)"
                                                    class="text-xs text-green-400 hover:text-green-300 transition duration-200">
                                                    ✓
                                                </button>
                                                <button @click="cancelEditPrice(index)"
                                                    class="text-xs text-red-400 hover:text-red-300 transition duration-200">
                                                    ✕
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right ml-4">
                                        <div class="text-lg font-bold text-red-primary">
                                            ރ<span x-text="(item.price * item.qty).toFixed(2)"></span>
                                        </div>
                                        <button @click="removeFromCart(index)"
                                            class="text-red-400 hover:text-red-300 text-sm transition duration-200 mt-1">
                                            🗑️ Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Checkout Panel -->
            <div class="bg-black-secondary rounded-lg border border-gray-dark p-6 h-fit sticky top-24">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    💳 Checkout
                </h2>

                <!-- Customer Selection -->
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Customer (Optional)</label>
                        <div class="flex space-x-2">
                            <div class="flex-1 relative">
                                <input type="text" x-model="customerSearch" placeholder="Search customers..."
                                    @input="filterCustomers()"
                                    class="w-full px-3 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary transition duration-200">
                                <div x-show="customerSearch && filteredCustomers.length > 0" x-cloak
                                    class="absolute top-full left-0 right-0 bg-gray-dark border border-gray-600 rounded-lg mt-1 max-h-48 overflow-y-auto z-10">
                                    <template x-for="customer in filteredCustomers" :key="customer.id">
                                        <div @click="selectCustomer(customer)"
                                            class="px-3 py-2 hover:bg-gray-600 cursor-pointer text-white border-b border-gray-600 last:border-b-0">
                                            <div class="font-medium" x-text="customer.name"></div>
                                            <div class="text-sm text-gray-400" x-text="customer.phone"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                        </div>
                        <div x-show="selectedCustomer" class="mt-2">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-400">
                                    Selected: <span class="text-white" x-text="getSelectedCustomerName()"></span>
                                </div>
                                <button @click="clearCustomer()"
                                    class="text-xs text-red-400 hover:text-red-300 transition duration-200">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Payment Method</label>
                        <select x-model="paymentMethod"
                            class="w-full px-3 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-primary transition duration-200">
                            <option value="cash">💵 Cash Payment</option>
                            <option value="bank_transfer">🏦 Bank Transfer</option>
                        </select>
                    </div>

                    <div x-show="paymentMethod === 'bank_transfer'" x-transition>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Reference Number</label>
                        <input type="text" x-model="referenceNumber" placeholder="Enter transaction reference"
                            class="w-full px-3 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary transition duration-200">
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t border-gray-600 pt-6 space-y-3 mb-6">
                    <div class="flex justify-between text-gray-300">
                        <span>Items (<span x-text="cart.reduce((sum, item) => sum + item.qty, 0)"></span>):</span>
                        <span>ރ<span x-text="subtotal.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-gray-300">
                        <span>Tax (0%):</span>
                        <span>ރ0.00</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-white border-t border-gray-600 pt-3">
                        <span>Total:</span>
                        <span class="text-red-primary">ރ<span x-text="total.toFixed(2)"></span></span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button @click="processPayment()" :disabled="cart.length === 0 || processing"
                        class="w-full bg-red-primary hover:bg-red-secondary disabled:bg-gray-600 disabled:cursor-not-allowed text-white py-4 px-4 rounded-lg font-semibold transition duration-200 flex items-center justify-center space-x-2">
                        <span x-show="!processing">💳</span>
                        <span x-show="processing" class="animate-spin">⏳</span>
                        <span x-text="processing ? 'Processing Sale...' : 'Complete Sale'"></span>
                    </button>

                    <button @click="clearCart()" :disabled="cart.length === 0"
                        class="w-full bg-gray-dark hover:bg-gray-600 disabled:bg-gray-700 disabled:cursor-not-allowed text-white py-3 px-4 rounded-lg transition duration-200">
                        🗑️ Clear Cart
                    </button>
                </div>

                <!-- Quick Stats -->
                <div class="mt-6 pt-6 border-t border-gray-600">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-red-primary" x-text="cart.length"></div>
                            <div class="text-xs text-gray-400">Items</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-primary">ރ<span x-text="total.toFixed(0)"></span>
                            </div>
                            <div class="text-xs text-gray-400">Total</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Success Modal -->
    <div x-show="showSuccessModal" x-cloak
        class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">
        <div class="bg-black-secondary border border-red-primary rounded-lg p-8 max-w-md mx-4 text-center"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="text-green-400 text-6xl mb-6">✅</div>
            <h3 class="text-2xl font-bold text-white mb-3">Sale Completed!</h3>
            <p class="text-gray-300 mb-6">Invoice #<span x-text="lastInvoiceId"></span> generated successfully</p>
            <div class="space-y-3">
                <button @click="printInvoice()"
                    class="w-full bg-red-primary hover:bg-red-secondary text-white py-3 px-4 rounded-lg font-semibold transition duration-200 flex items-center justify-center space-x-2">
                    <span>🖨️</span>
                    <span>Print Invoice</span>
                </button>
                <button @click="closeSuccessModal()"
                    class="w-full bg-gray-dark hover:bg-gray-600 text-white py-3 px-4 rounded-lg transition duration-200">
                    Continue Selling
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="processing" x-cloak
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-40">
        <div class="bg-black-secondary border border-red-primary rounded-lg p-8 text-center">
            <div class="animate-spin text-4xl mb-4">⏳</div>
            <p class="text-white font-semibold">Processing your sale...</p>
        </div>
    </div>

    <script>
        function posApp() {
            return {
                searchQuery: '',
                searchResults: [],
                cart: [],
                customers: [],
                selectedCustomer: '',
                paymentMethod: 'cash',
                referenceNumber: '',
                subtotal: 0,
                total: 0,
                processing: false,
                showSuccessModal: false,
                lastInvoiceId: null,
                filterType: 'all',
                isAuthenticated: false,

                customerSearch: '',
                filteredCustomers: [],

                init() {
                    this.checkAuthStatus();
                    this.loadCustomers();
                    // Update time every second
                    setInterval(() => {
                        this.$dispatch('time-update');
                    }, 1000);
                },

                async checkAuthStatus() {
                    try {
                        const response = await fetch('/debug');
                        const data = await response.json();
                        this.isAuthenticated = data.auth_check;
                        console.log('Auth status:', data.auth_check);
                    } catch (error) {
                        console.error('Failed to check auth status:', error);
                        this.isAuthenticated = false;
                    }
                },

                async loadCustomers() {
                    try {
                        console.log('Loading customers...');
                        const response = await fetch('/web-api/customers');
                        console.log('Customers response status:', response.status);

                        if (response.ok) {
                            const data = await response.json();
                            console.log('Customers loaded:', data);
                            this.customers = data;
                        } else {
                            console.error('Failed to load customers, status:', response.status);
                        }
                    } catch (error) {
                        console.error('Failed to load customers:', error);
                    }
                },

                async searchProducts() {
                    console.log('Search triggered:', this.searchQuery);

                    if (this.searchQuery.length < 2) {
                        this.searchResults = [];
                        return;
                    }

                    try {
                        let url = `/web-api/products/search?q=${encodeURIComponent(this.searchQuery)}`;
                        if (this.filterType !== 'all') {
                            url += `&type=${this.filterType}`;
                        }

                        console.log('Fetching URL:', url);
                        const response = await fetch(url);
                        console.log('Response status:', response.status);

                        if (response.ok) {
                            const data = await response.json();
                            console.log('Search results:', data);
                            this.searchResults = data;
                        } else {
                            console.error('Search failed with status:', response.status);
                            const errorText = await response.text();
                            console.error('Error response:', errorText);

                            // Show authentication error
                            if (response.status === 401 || response.status === 419) {
                                alert('❌ Authentication required! Please log in first.\n\nVisit /pos-login to auto-login as admin');
                            } else {
                                alert(`❌ Search failed: ${response.status} - ${errorText}`);
                            }
                            this.searchResults = [];
                        }
                    } catch (error) {
                        console.error('Failed to search products:', error);
                        alert('❌ Network error: ' + error.message);
                        this.searchResults = [];
                    }
                },

                quickAdd() {
                    if (this.searchResults.length === 1) {
                        this.addToCart(this.searchResults[0]);
                    } else if (this.searchResults.length > 1) {
                        // Auto-select first result
                        this.addToCart(this.searchResults[0]);
                    }
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);

                    if (existingItem) {
                        existingItem.qty += 1;
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: parseFloat(product.price),
                            originalPrice: parseFloat(product.price), // Store original price
                            qty: 1,
                            type: product.type,
                            stock_qty: product.stock_qty,
                            editingPrice: false
                        });
                    }

                    this.updateTotal();
                    this.searchQuery = '';
                    this.searchResults = [];

                    // Visual feedback
                    this.$dispatch('item-added', { product: product.name });
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.updateTotal();
                },

                increaseQty(index) {
                    this.cart[index].qty += 1;
                    this.updateTotal();
                },

                decreaseQty(index) {
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty -= 1;
                        this.updateTotal();
                    }
                },

                updateTotal() {
                    this.subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                    this.total = this.subtotal; // No tax for now
                },

                clearCart() {
                    this.cart = [];
                    this.updateTotal();
                    this.selectedCustomer = '';
                    this.customerSearch = '';
                    this.filteredCustomers = [];
                    this.paymentMethod = 'cash';
                    this.referenceNumber = '';
                },

                async processPayment() {
                    if (this.cart.length === 0) return;

                    this.processing = true;

                    try {
                        const response = await fetch('/web-api/pos/sale', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify({
                                cart: this.cart,
                                customer_id: this.selectedCustomer || null,
                                payment_method: this.paymentMethod,
                                reference_number: this.referenceNumber || null
                            })
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            this.lastInvoiceId = result.invoice_id;
                            this.showSuccessModal = true;
                            this.clearCart();
                        } else {
                            alert('Error processing sale: ' + (result.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Payment processing error:', error);
                        alert('Error processing sale: ' + error.message);
                    } finally {
                        this.processing = false;
                    }
                },

                printInvoice() {
                    if (this.lastInvoiceId) {
                        window.open(`/invoice/${this.lastInvoiceId}/pdf`, '_blank');
                    }
                },

                closeSuccessModal() {
                    this.showSuccessModal = false;
                    this.lastInvoiceId = null;
                },

                // Price editing functions
                startEditPrice(index) {
                    this.cart[index].editingPrice = true;
                    this.cart[index].newPrice = this.cart[index].price;
                    // Focus the input field after a short delay
                    setTimeout(() => {
                        const input = document.querySelector(`input[x-model.number="item.newPrice"]`);
                        if (input) input.focus();
                    }, 100);
                },

                savePrice(index) {
                    const item = this.cart[index];
                    const newPrice = parseFloat(item.newPrice);

                    // Validate price
                    if (isNaN(newPrice) || newPrice < 0) {
                        alert('Please enter a valid price (minimum 0)');
                        return;
                    }

                    // Update the price
                    item.price = newPrice;
                    item.editingPrice = false;
                    delete item.newPrice;

                    // Update total
                    this.updateTotal();

                    // Show feedback
                    this.$dispatch('price-updated', {
                        product: item.name,
                        oldPrice: item.originalPrice || item.price,
                        newPrice: newPrice
                    });
                },

                cancelEditPrice(index) {
                    this.cart[index].editingPrice = false;
                    delete this.cart[index].newPrice;
                },

                filterCustomers() {
                    if (!this.customerSearch.trim()) {
                        this.filteredCustomers = [];
                        return;
                    }

                    const search = this.customerSearch.toLowerCase();
                    this.filteredCustomers = this.customers.filter(customer =>
                        customer.name.toLowerCase().includes(search) ||
                        customer.phone.includes(search)
                    );
                },

                selectCustomer(customer) {
                    this.selectedCustomer = customer.id;
                    this.customerSearch = customer.name;
                    this.filteredCustomers = [];
                },

                getSelectedCustomerName() {
                    const customer = this.customers.find(c => c.id == this.selectedCustomer);
                    return customer ? `${customer.name} (${customer.phone})` : '';
                },

                clearCustomer() {
                    this.selectedCustomer = '';
                    this.customerSearch = '';
                    this.filteredCustomers = [];
                },


            }
        }
    </script>
</body>

</html>