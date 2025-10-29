
<x-layout>
    <div style="background-color:#dddbd9; min-height:100vh;">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ url()->previous() ?: route('menu') }}" class="inline-flex items-center text-green-900 hover:text-green-800 font-medium">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>


        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
                <div class="relative">
                    @php
                        $imageSrc = $product->image_path ? asset('storage/' . $product->image_path) : asset('images/logo.png');
                    @endphp
                    <img
                        src="{{ $imageSrc }}"
                        alt="{{ $product->dish_name }}"
                        class="w-full h-auto rounded-lg object-cover"
                    >
                </div>

                <div class="flex flex-col justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">
                            Menu > {{ $product->category->name ?? 'Category' }}
                        </p>

                        <h1 class="text-3xl md:text-4xl font-bold font-merriweather text-green-900 mb-4">
                            {{ $product->dish_name }}
                        </h1>

                        <div class="flex items-baseline gap-3 mb-6">
                            <span class="text-3xl font-bold text-green-900">
                                ₱{{ number_format($product->price, 2) }}
                            </span>
                        </div>

                        <p class="text-gray-700 leading-relaxed mb-6">
                            {{ $product->description ?? 'No description provided.' }}
                        </p>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Servings</label>
                            <input type="hidden" id="serving-input" value="standard">
                            <div class="flex gap-3">
                                <button id="size-standard" type="button"
                                        class="px-6 py-2 border-2 border-green-900 bg-green-900 text-white rounded-full font-medium hover:bg-green-800 transition">
                                    Standard
                                </button>
                                <button id="size-large" type="button"
                                        class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-green-900 hover:text-green-900 transition">
                                    Large
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center border-2 border-gray-300 rounded-lg">
                                <button id="qty-minus" class="px-4 py-2 text-gray-600 hover:text-green-900 font-bold" type="button">-</button>
                                <input
                                    id="qty-input"
                                    type="number"
                                    value="1"
                                    min="1"
                                    class="w-16 text-center border-x-2 border-gray-300 py-2 focus:outline-none"
                                >
                                <button id="qty-plus" class="px-4 py-2 text-gray-600 hover:text-green-900 font-bold" type="button">+</button>
                            </div>

                            <form method="POST" action="{{ route('cart.add', $product) }}" onsubmit="syncQty(this)" class="flex-1">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button class="w-full bg-green-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-800 transition" type="submit">
                                    Add To Cart
                                </button>
                            </form>

                            <form method="POST" action="{{ route('cart.add', $product) }}" onsubmit="syncQty(this)" class="">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="redirect" value="checkout">
                                <button class="bg-yellow-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition" type="submit">
                                    Buy Now
                                </button>
                            </form>
                        </div>

                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold">Category:</span>
                                <a href="{{ route('menu', ['category' => $product->category_id]) }}" class="text-green-900 hover:underline">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <div class="text-center mb-8">
                <h2 class="text-xs tracking-wider text-gray-500 uppercase mb-2">Related Products</h2>
                <h3 class="text-3xl font-bold font-merriweather text-green-900">
                    Explore <span class="text-yellow-600">Related Products</span>
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($relatedProducts as $related)
                    <a href="{{ route('menu.show', $related->id) }}" class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group block">
                        <div class="relative overflow-hidden">
                            @php
                                $rImg = $related->image_path ? asset('storage/' . $related->image_path) : asset('images/logo.png');
                            @endphp
                            <img
                                src="{{ $rImg }}"
                                alt="{{ $related->dish_name }}"
                                class="w-full h-64 object-cover group-hover:scale-110 transition duration-300"
                            >
                        </div>

                        <div class="p-4">
                            <p class="text-xs text-gray-500 mb-1">{{ $related->category->name ?? 'Menu' }}</p>

                            <h4 class="text-lg font-semibold text-gray-800 mb-2 truncate">
                                {{ $related->dish_name }}
                            </h4>

                            <div class="flex items-center justify-between">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-xl font-bold text-green-900">
                                        ₱{{ number_format($related->price, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500">No related products found.</div>
                @endforelse
            </div>
        </div>
    </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const qtyInput = document.getElementById('qty-input');
            const btnMinus = document.getElementById('qty-minus');
            const btnPlus = document.getElementById('qty-plus');

            const sizeStd = document.getElementById('size-standard');
            const sizeLrg = document.getElementById('size-large');
            const servingInput = document.getElementById('serving-input');

            btnPlus?.addEventListener('click', () => {
                const current = parseInt(qtyInput.value || '1', 10);
                qtyInput.value = isNaN(current) ? 1 : current + 1;
            });

            btnMinus?.addEventListener('click', () => {
                const current = parseInt(qtyInput.value || '1', 10);
                const next = Math.max(1, isNaN(current) ? 1 : current - 1);
                qtyInput.value = next;
            });

            qtyInput?.addEventListener('input', () => {
                const v = parseInt(qtyInput.value || '1', 10);
                if (isNaN(v) || v < 1) qtyInput.value = 1;
            });

            const active = ['border-green-900','bg-green-900','text-white'];
            const inactive = ['border-gray-300','text-gray-700','hover:border-green-900','hover:text-green-900'];

            function setActive(btn) {
                inactive.forEach(c => btn.classList.remove(c));
                active.forEach(c => btn.classList.add(c));
            }
            function setInactive(btn) {
                active.forEach(c => btn.classList.remove(c));
                inactive.forEach(c => btn.classList.add(c));
            }
            function selectSize(size) {
                servingInput.value = size;
                if (size === 'standard') {
                    setActive(sizeStd); setInactive(sizeLrg);
                } else {
                    setInactive(sizeStd); setActive(sizeLrg);
                }
            }

            sizeStd?.addEventListener('click', () => selectSize('standard'));
            sizeLrg?.addEventListener('click', () => selectSize('large'));
        });

        // Sync the visible qty input into hidden form inputs before submit
        window.syncQty = function(form) {
            const qty = document.getElementById('qty-input')?.value || '1';
            const input = form.querySelector('input[name="quantity"]');
            if (input) input.value = Math.max(1, parseInt(qty || '1', 10));
        }
    </script>

    @if(session('success'))
        <div id="addToCartModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <div class="flex items-start justify-between">
                    <h3 class="text-xl font-semibold text-emerald-900">Success</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('addToCartModal').remove()">✕</button>
                </div>
                <p class="mt-2 text-gray-700">{{ session('success') }}</p>
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('cart.index') }}" class="flex-1 text-center bg-green-900 hover:bg-green-800 text-white px-4 py-2 rounded transition-colors">Go to Cart</a>
                    <a href="{{ route('checkout.index') }}" class="flex-1 text-center border border-green-900 text-green-900 px-4 py-2 rounded hover:bg-green-50 transition-colors">Checkout</a>
                </div>
            </div>
        </div>
    @endif
</x-layout>
