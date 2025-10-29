@props([
    'dish_name' => 'Dish Name',
    'price' => 0,
    'description' => '',
    'image_path' => null,
    'href' => null,
])

@php
    $imgSrc = $image_path ?: asset('images/logo.png');
@endphp


@if($href)
    <a href="{{ $href }}" aria-label="View {{ $dish_name }} details"
       class="group block max-w-sm mx-8 rounded-xl overflow-hidden shadow-md bg-white h-auto hover:shadow-xl transition-shadow duration-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-600">
        <div class="bg-gray-800 h-56 overflow-hidden">
            <img src="{{ $imgSrc }}" alt="{{ $dish_name }}" class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105">
        </div>

        <div class="p-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-gray-900 group-hover:underline">{{ $dish_name }}</h2>
                <span class="text-emerald-900 font-bold text-lg">₱{{ number_format($price, 2) }}</span>
            </div>

            <p class="text-sm text-gray-600">
                {{ Str::limit($description, 80) }}
                <span class="font-semibold text-black">Read More</span>

            </p>
        </div>
    </a>
@else
    <div class="max-w-sm mx-8 rounded-xl overflow-hidden shadow-md bg-white h-auto hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gray-800 h-56 overflow-hidden">
            <img src="{{ $imgSrc }}" alt="{{ $dish_name }}" class="w-full h-full object-cover">
        </div>

        <div class="p-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-gray-900">{{ $dish_name }}</h2>
                <span class="text-emerald-900 font-bold text-lg">₱{{ number_format($price, 2) }}</span>
            </div>

            <p class="text-sm text-gray-600">
                {{ Str::limit($description, 80) }}
                <span class="font-semibold text-black">Read More</span>
            </p>
        </div>
    </div>
@endif
