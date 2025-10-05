<x-layout>
    <div class="min-h-screen w-full overflow-x-hidden flex flex-col" style="background-color: #dddbd9;">
        <!-- Hero -->
        <section class="relative flex flex-col items-center justify-center text-center gap-6 px-4 py-16 md:py-20">
            <h1 class="text-emerald-900 font-bold font-merriweather text-4xl md:text-5xl lg:text-6xl max-w-4xl">About PingganPH</h1>
            <!-- Responsive hero images -->
            <div class="w-full max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                <div class="aspect-[4/3] overflow-hidden rounded-2xl">
                    <img src="/images/adobo.jpg" alt="Adobo" class="w-full h-full object-cover" />
                </div>
                <div class="aspect-[4/3] overflow-hidden rounded-2xl">
                    <img src="/images/gata.jpg" alt="Gata" class="w-full h-full object-cover" />
                </div>
                <div class="aspect-[4/3] overflow-hidden rounded-2xl">
                    <img src="/images/karekare.jpg" alt="Kare-Kare" class="w-full h-full object-cover" />
                </div>
            </div>
            <p class="font-poppins text-gray-700 text-base md:text-lg max-w-3xl">Your trusted partner in choosing delicious meals at your fingertips.</p>
        </section>

        <!-- main content section  -->
        <section class="py-12 md:py-16 px-4 md:px-8 lg:px-12 flex flex-col w-full mb-12">
            <div class="w-full space-y-24 max-w-6xl mx-auto">
                <!-- intro -->
                <section class="text-center">
                    <h2 class="font-merriweather text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">Who We Are</h2>
                    <p class="font-poppins text-gray-700 text-base md:text-lg max-w-3xl mx-auto mb-10">PingganPH brings quality homemade Filipino meals straight to your hands. What started as a humble Karinderya in Kabacan is now online—serving you your favorite ulam, anytime, anywhere.</p>

                <!-- mission statement -->
                    <h2 class="font-merriweather text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">Mission Statement</h2>
                    <p class="font-poppins text-gray-700 text-base md:text-lg max-w-3xl mx-auto mb-10">Our mission is to make homestyle meals accessible to everyone, combining tradition with the convenience of modern technology.</p>
                </section>

                <!-- our story  -->
                <section class="text-center">
                    <h2 class="font-merriweather text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">Our Story</h2>
                    <div class="space-y-5 text-base md:text-lg font-poppins text-gray-700 max-w-3xl mx-auto mb-6">
                        <p>PingganPH was born from a simple idea: to venture and order quality food accessible to everyone, anytime. It is a small local service yet it can connect hundreds of food lovers.</p>
                        <p>We focus on speed, quality, and an extensive selection of cuisines from the local eatery.</p>
                        <p>Today, we continue to innovate with seamless experiences that make every meal special.</p>
                        <p>Human connection matters—people love knowing the heart behind the food.</p>
                    </div>
                </section>

                <section class="space-y-8 w-full mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-6xl mx-auto">
                        <!-- what makes us different -->
                        <section class="flex-1 bg-amber-500 p-6 md:p-8 rounded-lg shadow-md min-h-[240px] flex flex-col">
                            <div class="flex-1 flex flex-col">
                                <h3 class="font-merriweather text-xl md:text-2xl font-bold text-gray-900 mb-4 text-center">What Makes Us Different</h3>
                                <ul class="space-y-2 md:space-y-3 text-base md:text-lg font-poppins text-gray-800 list-disc list-inside text-left flex-1">
                                    <li>Homestyle, lutong-bahay taste</li>
                                    <li>Fresh and affordable meals</li>
                                    <li>Convenient online ordering system</li>
                                    <li>Fast local delivery</li>
                                </ul>
                                <p class="font-poppins text-gray-700 text-base md:text-lg italic mt-4 text-left">Unlike big fast-food chains, we cook with care and tradition—just like how mom makes it at home.</p>
                            </div>
                        </section>

                        <!-- how it works -->
                        <section class="flex-1 bg-amber-500 p-6 md:p-8 rounded-lg shadow-md min-h-[240px] flex flex-col">
                            <div class="flex-1 flex flex-col">
                                <h3 class="font-merriweather text-xl md:text-2xl font-bold text-gray-900 mb-4 text-center">How It Works</h3>
                                <ul class="space-y-2 md:space-y-3 text-base md:text-lg font-poppins text-gray-800 list-disc list-inside text-left flex-1">
                                    <li>Browse the menu</li>
                                    <li>Add to cart</li>
                                    <li>Place your order</li>
                                    <li>Track delivery</li>
                                    <li>Enjoy your meal</li>
                                </ul>
                                <p class="font-poppins text-gray-700 text-base md:text-lg italic mt-4 text-left">Simple steps to get your favorite meals delivered quickly and hassle-free.</p>
                            </div>
                        </section>
                    </div>
                </section>

                <!-- community & customers -->
                <section class="text-center bg-amber-500 p-8 md:p-12 rounded-lg shadow-md max-w-4xl mx-auto mb-4">
                    <h3 class="font-merriweather text-xl md:text-2xl font-bold text-gray-900 mb-2">Community & Customers</h3>
                    <p class="font-poppins text-gray-700 text-base md:text-lg mb-2">Every order supports local cooks, local suppliers, and the tradition of Filipino dining.</p>
                </section>

                <!-- CTA -->
                <div class="pt-4 flex justify-center mb-2">
                    <x-menuButton href="{{ route('menu') }}">Order Now</x-menuButton>
                </div>
            </div>
        </section>
    </div>

    <x-footer />
</x-layout>
