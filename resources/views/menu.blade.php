<x-layout>
    <div class="min-h-screen" style="background-color: #dddbd9;">
        <!-- Header Section -->
        <div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-emerald-900 mb-4 font-merriweather">Our Menu</h1>
                <p class="text-lg text-gray-700 font-poppins max-w-2xl mx-auto">Discover our delicious selection of dishes, carefully crafted with love and tradition</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <form method="GET" action="{{ route('menu') }}" class="space-y-4">
                    <!-- Search Bar -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text"
                                   name="search"
                                   value="{{ $search }}"
                                   placeholder="Search dishes..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Price Range -->
                        <div class="flex gap-2">
                            <input type="number"
                                   name="min_price"
                                   value="{{ $minPrice }}"
                                   placeholder="Min Price"
                                   class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <span class="flex items-center text-gray-500">-</span>
                            <input type="number"
                                   name="max_price"
                                   value="{{ $maxPrice }}"
                                   placeholder="Max Price"
                                   class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>

                        <button type="submit"
                                class="px-6 py-2 bg-green-900 text-white rounded-lg hover:bg-green-800 transition-colors">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Category Filter -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <!-- Desktop: Tabs -->
                <div class="hidden md:flex flex-wrap gap-2">
                    <a href="{{ route('menu') }}"
                       class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !$selectedCategory ? 'bg-green-900 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        All ({{ $categories->sum('products_count') }})
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('menu', ['category' => $category->id]) }}"
                           class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $selectedCategory == $category->id ? 'bg-green-900 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            {{ $category->name }} ({{ $category->products_count }})
                        </a>
                    @endforeach
                </div>

                <!-- Mobile: Dropdown -->
                <div class="md:hidden">
                    <form method="GET" action="{{ route('menu') }}" class="space-y-4">
                        <!-- Preserve other filters -->
                        @if($search)
                            <input type="hidden" name="search" value="{{ $search }}">
                        @endif
                        @if($minPrice)
                            <input type="hidden" name="min_price" value="{{ $minPrice }}">
                        @endif
                        @if($maxPrice)
                            <input type="hidden" name="max_price" value="{{ $maxPrice }}">
                        @endif

                        <select name="category" onchange="this.form.submit()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Categories ({{ $categories->sum('products_count') }})</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->products_count }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <!-- Results Count -->
            @if($search || $selectedCategory || $minPrice || $maxPrice)
                <div class="mb-6">
                    <p class="text-gray-600">
                        Showing {{ $menu->count() }} of {{ $menu->total() }} dishes
                        @if($search)
                            for "{{ $search }}"
                        @endif
                    </p>
                </div>
            @endif

            <!-- Products Grid -->
            @if($menu->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($menu as $product)
                        <x-components.product-card
                            :dish_name="$product->dish_name"
                            :price="$product->price"
                            :description="$product->description"
                            :image_path="asset('storage/' . $product->image_path)"
                            :product_id="$product->id"
                            :href="route('menu.show', $product->id)"/>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($menu->hasPages())
                    <div class="mt-8">
                        {{ $menu->links() }}
                    </div>
                @endif
            @else
                <!-- No Results -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 0 1 5.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0 1 12 15c-2.34 0-4.29-1.009-5.824-2.709M15 6.291A7.962 7.962 0 0 0 12 4c-2.34 0-4.29 1.009-5.824 2.709"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No dishes found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($search || $selectedCategory || $minPrice || $maxPrice)
                            Try adjusting your search or filter criteria.
                        @else
                            No dishes are available at the moment.
                        @endif
                    </p>
                    @if($search || $selectedCategory || $minPrice || $maxPrice)
                        <div class="mt-4">
                            <a href="{{ route('menu') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200">
                                Clear filters
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

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
