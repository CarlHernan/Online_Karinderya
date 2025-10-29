<footer class="bg-primary text-gray-800 px-6 md:px-20 py-10">
    <div class="max-w-7xl mx-auto">
        <!-- Top CTA -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-green-900 mb-3">Don't Wait – Order Now!</h2>
            <p class="text-gray-600 max-w-2xl mx-auto mb-6">PingganPH is your go-to spot for everyday favorites—so hurry before the trays run out and you miss your chance to grab the ulam you love!</p>
            <x-menuButton href="{{ route('menu') }}">Explore Our Menu</x-menuButton>
       </div>

        <!-- Divider -->
        <div class="border-t border-gray-300 my-8"></div>

        <!-- Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-sm">
            <!-- Contact -->
            <div>
                <h3 class="font-semibold mb-3 text-lg">Contact</h3>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-center gap-2 text-green-900 font-sans">
                        <span>📞</span> +639855330897
                    </li>
                    <li class="flex items-start gap-2 text-green-900 font-sans">
                        <span>📍</span>USM Avenue, Kabacan,<br>North Cotabato, Philippines
                    </li>
                    <li class="flex items-center gap-2 text-green-900 font-sans">
                        <span>✉️</span> group7@gmail.com
                    </li>
                </ul>
            </div>

            <!-- Navigate -->
            <div class="hidden md:block">
                <h3 class="font-semibold mb-3 text-lg">Navigate</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:underline text-green-900 font-sans">Home</a></li>
                    <li><a href="{{ route('menu') }}" class="hover:underline text-green-900 font-sans">Menu</a></li>
                    <li><a href="{{ route('orders') }}" class="hover:underline text-green-900 font-sans">Orders</a></li>
                    <li><a href="{{ route('about') }}" class="hover:underline text-green-900 font-sans">About</a></li>
                </ul>
            </div>

            <!-- Menu -->
            <div>
                <h3 class="font-semibold mb-3 text-lg">Menu</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('menu') }}" class="hover:underline text-green-900 font-sans">All Categories</a></li>
                    <li><a href="{{ route('menu', ['category' => 1]) }}" class="hover:underline text-green-900 font-sans">Ulam</a></li>
                    <li><a href="{{ route('menu', ['category' => 2]) }}" class="hover:underline text-green-900 font-sans">Kanin</a></li>
                    <li><a href="{{ route('menu', ['category' => 3]) }}" class="hover:underline text-green-900 font-sans">Gulay</a></li>
                    <li><a href="{{ route('menu', ['category' => 4]) }}" class="hover:underline text-green-900 font-sans">Sabaw</a></li>
                </ul>
            </div>

            <!-- Follow Us -->
            <div>
                <h3 class="font-semibold mb-3 text-lg">Follow Us</h3>
                <ul class="space-y-2">
                    <li><a href="https://www.facebook.com/justine.pajes.56" class="hover:underline text-green-900">Facebook</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom -->
        <div
            class="border-t border-gray-300 mt-10 pt-6 flex flex-col md:flex-row justify-center items-center text-xs text-gray-600">
            <p>©2025, PingganPH | All rights reserved.</p>
        </div>
    </div>
</footer>
