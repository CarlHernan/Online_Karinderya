<nav
    class="bg-transparent backdrop-blur-lg sticky top-0 inset-x-0 z-50 px-6 py-1 md:py-2 flex items-center justify-between font-montserrat border-0 shadow-md">
    <a href="/home" class="flex items-center gap-1 mb-1" aria-label="Go to homepage">
        <img src="{{ asset('images/logo.png') }}" alt="PingganPH logo" loading="lazy" class="h-6">
        <div class="font-bold text-lg mt-2 text-black">Pinggan<span class="text-emerald-800">PH</span></div>
    </a>

    <div class="flex items-center md:gap-8 gap-2 ">
        <div class="hidden md:flex items-center">
            <ul class="flex gap-8 rounded-md px-2 py-1 items-center">
                <li>
                    <a href="/home"
                       class="{{ request()->Is('home') ? 'text-emerald-900 underline underline-offset-3 transform transition-transform duration-150' : 'text-emerald-900 transform transition-transform duration-150 hover:scale-110 inline-block ' }} ">Home</a>
                </li>
                <li>
                    <a href="/menu"
                       class="{{ request()->Is('menu*') ? 'text-emerald-900 underline underline-offset-3 transform transition-transform duration-150' : 'text-emerald-900 inline-block transform transition-transform duration-150 hover:scale-110' }}">Menu</a>
                </li>
                <li>
                    <a href="/orders"
                       class="{{ request()->Is('orders*') ? 'text-emerald-900 underline underline-offset-3 transform transition-transform duration-15' : 'text-emerald-900 transform duration-150 hover:scale-110 inline-block' }} ">Orders</a>
                </li>
                <li>
                    <a href="/about"
                       class="{{ request()->Is('about*') ? 'text-emerald-900 underline underline-offset-3 transform transition-transform duration-15' : 'text-emerald-900 transform duration-150 hover:scale-110 inline-block' }} ">About</a>
                </li>
            </ul>
        </div>


    </div>
    {{-- count items in the cart mga bai --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('cart.index') }}" aria-label="Go to cart" class="text-emerald-900 md:border-hidden border-r-1 border-gray-300 pr-1 relative">
            @include('components.icons.cart')
            @php
                $cartCount = collect(session('cart', []))->sum('quantity');
            @endphp
            @if($cartCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">{{ $cartCount }}</span>
            @endif
        </a>

        @if(Auth::guard('web')->check())
            <!-- User profile dropdown (web users) -->
            <div x-data="{ open: false }" class="relative hidden md:block">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <img src="{{ Auth::user()->avatar ?? asset('assets/images/users/1.jpg') }}" alt="{{ Auth::user()->name ?? 'User' }}" class="w-8 h-8 rounded-full">
                    <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'User' }}</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white text-gray-700 rounded-md shadow-lg py-1 z-50 border border-gray-200">
                    <a href="{{ route('user.profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">My Profile</a>
                    <div class="border-t border-gray-200"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <!-- Login/Register buttons -->
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('login') }}" class="text-emerald-900 hover:underline">Login</a>
                <a href="{{ route('register') }}" class="text-white bg-emerald-700 hover:bg-emerald-800 px-3 py-1 rounded">Register</a>
            </div>
        @endif

        <button id="menuBtn" class="md:hidden pl-1  text-emerald-900 border-black">
            @include('components.icons.hamburger')
        </button>

        {{-- Mobile nav modal --}}
        <div id="navModal" class="hidden fixed inset-0 z-40 flex flex-col">

            <div id="modalBackdrop" class="absolute inset-0"></div>


            <div class="relative w-full bg-gray-100 shadow-md flex flex-col items-center py-10 animate-slideDown">

                <button id="closeBtn" class="absolute top-5 right-6 text-2xl">
                    @include('components.icons.close')
                </button>


                <nav class="mt-10">
                    <ul class="text-2xl font-semibold text-gray-900 space-y-8 text-center">
                        <li class="nav-link opacity-0"><a href="/home" class="block hover:text-emerald-700">Home</a></li>
                        <li class="nav-link opacity-0"><a href="/menu" class="block hover:text-emerald-700">Products</a></li>
                        <li class="nav-link opacity-0"><a href="/orders" class="block hover:text-emerald-700">Orders</a></li>
                        <li class="nav-link opacity-0"><a href="/about" class="block hover:text-emerald-700">About</a></li>
                        @if(Auth::guard('web')->check())
                            <li class="nav-link opacity-0"><a href="{{ route('user.profile.edit') }}" class="block hover:text-emerald-700">My Profile</a></li>
                            <li class="nav-link opacity-0">
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="hover:text-emerald-700">Logout</button>
                                </form>
                            </li>
                        @else
                            <li class="nav-link opacity-0"><a href="{{ route('login') }}" class="block hover:text-emerald-700">Login</a></li>
                            <li class="nav-link opacity-0"><a href="{{ route('register') }}" class="block hover:text-emerald-700">Register</a></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>

    </div>

    {{-- Removed cart drawer preview per request --}}

</nav>

<script>
    //Hastag i love vanila ^^
    document.addEventListener('DOMContentLoaded', () => {
        const navModal = document.querySelector("#navModal");
        const menuBtn = document.querySelector("#menuBtn");
        const closeBtn = document.querySelector("#closeBtn");
        const navLinks = document.querySelectorAll("#navModal .nav-link");

        menuBtn.addEventListener('click', () => {
            navModal.classList.remove('hidden');
            navModal.classList.add('show');

            //Ito yung pa isa isa sila naga baba
            navLinks.forEach((link, i) => {
                setTimeout(() => {
                    link.classList.add('show');
                }, i * 200);
            });
        });

        closeBtn.addEventListener('click', () => {
            navModal.classList.add('hidden');
            navModal.classList.remove('show');
            navLinks.forEach(link => link.classList.remove('show'));
        });

        // Cart click now routes directly via anchor link; no JS needed
    });
</script>
