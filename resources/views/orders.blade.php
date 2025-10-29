<x-layout>
    <div class="min-h-screen" style="background-color: #dddbd9;">
        <div class="max-w-6xl mx-auto p-4">
            @if(isset($isGuest) && $isGuest)
                <div class="bg-white rounded-lg p-8 shadow-sm text-center">
                    <h2 class="text-2xl font-merriweather text-emerald-900 mb-2">You're not logged in</h2>
                    <p class="text-gray-600 mb-6">Please log in to view your orders.</p>
                    <div class="flex items-center justify-center gap-3">
                        <a href="{{ route('login') }}" class="px-5 py-2 bg-green-900 hover:bg-green-800 text-white rounded">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2 border border-green-900 text-green-900 rounded hover:bg-green-50">Register</a>
                    </div>
                </div>
            @else
                <!-- Filter Tabs -->
                <div class="bg-white rounded-lg p-4 mb-4 shadow-sm">
                    @php
                        $status = $status ?? request('status', 'all');
                        $filters = [
                            'all' => 'All',
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'preparing' => 'Preparing',
                            'ready' => 'Ready',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ];
                    @endphp
                    <div class="flex gap-3 flex-wrap">
                        @foreach($filters as $key => $label)
                            <a href="{{ route('orders', ['status' => $key]) }}"
                               class="px-6 py-2 rounded-full text-sm font-medium transition-all {{ ($status === $key) ? 'bg-green-900 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if(($orders ?? collect())->isEmpty())
                    <div class="bg-white rounded-lg p-8 shadow-sm text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No orders found</h3>
                        <p class="text-gray-600">Once you place an order, it will show up here.</p>
                        <a href="{{ route('menu') }}" class="inline-block mt-4 px-5 py-2 bg-green-900 hover:bg-green-800 text-white rounded">Browse Menu</a>
                    </div>
                @else
                    <div id="ordersContainer">
                        @foreach($orders as $order)
                            <div class="bg-white rounded-lg p-6 mb-4 shadow-sm">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                                        <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-gray-600">
                                            <span class="px-2 py-0.5 rounded-full bg-gray-100">Payment: <span class="font-medium">{{ strtoupper($order->payment_method ?? 'N/A') }}</span></span>
                                            @if(!empty($order->delivery_address))
                                                <span class="px-2 py-0.5 rounded-full bg-gray-100">Address: <span class="font-medium">{{ \Illuminate\Support\Str::limit($order->delivery_address, 60) }}</span></span>
                                            @endif
                                        </div>
                                    </div>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'preparing' => 'bg-orange-100 text-orange-800',
                                            'ready' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                <div class="space-y-4">
                                    @foreach($order->orderItems as $item)
                                        <div class="flex gap-4">
                                            @if($item->product && $item->product->image_path)
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->dish_name ?? $item->product->name }}" class="w-24 h-24 object-cover rounded-lg">
                                            @else
                                                <img src="{{ asset('images/logo.png') }}" alt="Product" class="w-24 h-24 object-cover rounded-lg">
                                            @endif
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start mb-1">
                                                    <h4 class="text-base font-semibold">{{ $item->product->dish_name ?? $item->product->name }}</h4>
                                                    <span class="text-base font-semibold">₱{{ number_format($item->price, 2) }}</span>
                                                </div>
                                                <div class="text-sm text-gray-600 flex justify-between">
                                                    <span>Qty: {{ $item->quantity }}</span>
                                                    <span class="tabular-nums">Line total: ₱{{ number_format($item->quantity * $item->price, 2) }}</span>
                                                </div>
                                                @if(!empty($item->notes))
                                                    <p class="text-xs text-gray-500 mt-1">{{ $item->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex justify-between items-center pt-4 mt-4 border-t">
                                    <div class="text-sm text-gray-600">
                                        @if($order->payment)
                                            Payment: <span class="font-medium">{{ ucfirst($order->payment->status) }}</span>
                                        @else
                                            Payment: <span class="font-medium">N/A</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg font-bold">Order Total: ₱{{ number_format($order->total_amount, 2) }}</span>
                                        <form method="POST" action="{{ route('orders.buy-again', $order) }}">
                                            @csrf
                                            <button type="submit" class="px-6 py-2 border-2 border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">Buy Again</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-layout>