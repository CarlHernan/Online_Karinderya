<x-layout>
<div class="min-h-screen" style="background-color: #dddbd9;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
        <h1 class="text-3xl font-bold text-emerald-900 mb-6 font-merriweather">Checkout</h1>

        @if(session('success'))
            <div id="orderSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                    <div class="flex items-start justify-between">
                        <h3 class="text-xl font-semibold text-emerald-900">Order placed!</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('orderSuccessModal').remove()">✕</button>
                    </div>
                    <p class="mt-2 text-gray-700">{{ session('success') }}</p>
                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('orders') }}" class="flex-1 text-center bg-green-900 hover:bg-green-800 text-white px-4 py-2 rounded transition-colors">View My Orders</a>
                        <a href="{{ route('menu') }}" class="flex-1 text-center border border-green-900 text-green-900 px-4 py-2 rounded hover:bg-green-50 transition-colors">Back to Menu</a>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Order details -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                <div class="divide-y">
                    @foreach($items as $item)
                        <div class="py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-12 h-12 object-cover rounded mr-4">
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                    <div class="text-xs text-gray-500">Qty: {{ $item['quantity'] }}</div>
                                </div>
                            </div>
                            <div class="text-sm font-medium text-gray-900 tabular-nums">₱{{ number_format($item['subtotal'], 2) }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 flex justify-between text-base font-bold text-gray-900">
                    <span>Total</span>
                    <span class="tabular-nums">₱{{ number_format($total, 2) }}</span>
                </div>
            </div>

            <!-- Right: Payment and delivery -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Delivery & Payment</h2>
                <form method="POST" action="{{ route('checkout.place') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Delivery Address</label>
                        <textarea name="address" rows="3" class="mt-1 block w-full border rounded px-3 py-2" required>{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <div class="space-y-2">
                            @if($paymentSettings['cod_enabled'] ?? true)
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="payment_method" value="cod" class="text-emerald-700" checked>
                                <span>Cash on Delivery (COD)</span>
                            </label>
                            @endif
                            @if($paymentSettings['gcash_enabled'] ?? false)
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="payment_method" value="gcash" class="text-emerald-700" {{ ($paymentSettings['cod_enabled'] ?? true) ? '' : 'checked' }}>
                                <span>GCash</span>
                            </label>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-green-900 text-white px-4 py-2 rounded hover:bg-green-800 transition-colors">Place Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layout>
