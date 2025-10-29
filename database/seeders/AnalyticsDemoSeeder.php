<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AnalyticsDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve models dynamically to avoid hard failures if namespaces differ
        $User = app()->make('App\\Models\\User');
        $Product = app()->make('App\\Models\\Product');
        $Order = app()->make('App\\Models\\Order');
        $OrderItem = app()->make('App\\Models\\OrderItem');
        $Payment = app()->make('App\\Models\\Payment');

        DB::transaction(function () use ($User, $Product, $Order, $OrderItem, $Payment) {
            // Ensure at least some products exist
            if ($Product::count() < 8) {
                $demoProducts = [
                    ['dish_name' => 'Chicken Adobo', 'price' => 120.00],
                    ['dish_name' => 'Pork Sinigang', 'price' => 140.00],
                    ['dish_name' => 'Beef Caldereta', 'price' => 160.00],
                    ['dish_name' => 'Pancit Bihon', 'price' => 90.00],
                    ['dish_name' => 'Lumpiang Shanghai', 'price' => 80.00],
                    ['dish_name' => 'Bangus Sisig', 'price' => 150.00],
                    ['dish_name' => 'Ginataang Gulay', 'price' => 100.00],
                    ['dish_name' => 'Chocolate Drink', 'price' => 50.00],
                ];
                foreach ($demoProducts as $p) {
                    $Product::create([
                        'dish_name' => $p['dish_name'],
                        'price' => $p['price'],
                        'description' => $p['dish_name'] . ' delicious homestyle meal.',
                        'image_path' => null,
                        'category_id' => $Product::first()->category_id ?? null,
                    ]);
                }
            }

            // Create demo customers (non-admin) if needed
            $numCustomersToAdd = max(0, 12 - $User::where('is_admin', false)->count());
            for ($i = 0; $i < $numCustomersToAdd; $i++) {
                $name = fake()->name();
                $User::create([
                    'name' => $name,
                    'email' => Str::slug($name) . rand(100,999) . '@example.test',
                    'password' => Hash::make('password'),
                    'is_admin' => false,
                    'is_active' => true,
                ]);
            }

            $customers = $User::where('is_admin', false)->inRandomOrder()->get();
            $products = $Product::inRandomOrder()->get();

            if ($customers->isEmpty() || $products->isEmpty()) {
                return; // Nothing to seed against
            }

            // Seed orders over the last 90 days for better charts
            $startDate = Carbon::now()->subDays(90)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $statusWeights = [
                'pending' => 10,
                'confirmed' => 15,
                'preparing' => 15,
                'ready' => 10,
                'delivered' => 40,
                'cancelled' => 10,
            ];

            $statusPool = collect($statusWeights)
                ->flatMap(function ($weight, $status) {
                    return array_fill(0, $weight, $status);
                })->values()->all();

            $paymentMethodPool = ['cod', 'gcash', 'cod', 'cod', 'gcash'];

            // Per day generate 0-12 orders
            $cursor = $startDate->copy();
            while ($cursor->lessThanOrEqualTo($endDate)) {
                $ordersToday = rand(0, 12);
                for ($i = 0; $i < $ordersToday; $i++) {
                    $user = $customers->random();
                    $status = $statusPool[array_rand($statusPool)];

                    // Create order
                    $order = $Order::create([
                        'user_id' => $user->id,
                        'status' => $status,
                        'notes' => null,
                        'created_at' => $cursor->copy()->addMinutes(rand(0, 1439)),
                        'updated_at' => Carbon::now(),
                    ]);

                    // Add 1-4 items
                    $numItems = rand(1, 4);
                    $total = 0;
                    for ($j = 0; $j < $numItems; $j++) {
                        $product = $products->random();
                        $qty = rand(1, 3);
                        $price = (float) $product->price;
                        $subtotal = $qty * $price;
                        $OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $qty,
                            'price' => $price,
                            'created_at' => $order->created_at,
                            'updated_at' => $order->updated_at,
                        ]);
                        $total += $subtotal;
                    }

                    // Persist computed total if the model has field (ignore if different schema)
                    if ($Order->getConnection()->getSchemaBuilder()->hasColumn($Order->getTable(), 'total_amount')) {
                        $order->total_amount = round($total, 2);
                        $order->save();
                    }

                    // Payments for delivered/confirmed/ready/preparing often exist; pending sometimes; cancelled rarely
                    $shouldCreatePayment = in_array($status, ['confirmed','preparing','ready','delivered'])
                        || ($status === 'pending' && rand(0, 100) < 30);

                    if ($shouldCreatePayment) {
                        $paymentStatus = $status === 'cancelled' ? 'failed' : (rand(0, 100) < 85 ? 'paid' : 'pending');
                        $method = $paymentMethodPool[array_rand($paymentMethodPool)];
                        $amount = $Order->getConnection()->getSchemaBuilder()->hasColumn($Order->getTable(), 'total_amount')
                            ? ($order->total_amount ?? $total)
                            : $total;

                        $Payment::create([
                            'order_id' => $order->id,
                            'payment_method' => $method,
                            'status' => $paymentStatus,
                            'amount' => round($amount, 2),
                            'proof_path' => null,
                            'created_at' => $order->created_at->copy()->addMinutes(rand(5, 180)),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }

                $cursor->addDay();
            }
        });
    }
}


