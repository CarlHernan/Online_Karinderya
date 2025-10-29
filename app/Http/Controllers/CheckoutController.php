<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;

class CheckoutController extends Controller
{
    // Show checkout page
    public function index(Request $request)
    {
        $cart = session('cart', []);
        $items = collect($cart)->map(function ($item) {
            $item['subtotal'] = $item['price'] * $item['quantity'];
            return $item;
        });
        $total = $items->sum('subtotal');

        // Load payment method settings
        $settings = file_exists(config_path('payment_methods.php'))
            ? include config_path('payment_methods.php')
            : ['gcash_enabled' => true, 'cod_enabled' => true];

        return view('checkout.index', [
            'items' => $items,
            'total' => $total,
            'user' => Auth::user(),
            'paymentSettings' => $settings,
        ]);
    }

    // Place order
    public function place(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:50',
            'payment_method' => 'required|in:cod,gcash',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $items = collect($cart)->map(function ($item) {
            $item['subtotal'] = $item['price'] * $item['quantity'];
            return $item;
        });
        $total = $items->sum('subtotal');

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'delivery_address' => $request->address,
                'customer_name' => $request->name,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
            ]);

            // Attach items if orders_items table exists; otherwise store JSON
            if (\Schema::hasTable('order_items')) {
                foreach ($items as $it) {
                    DB::table('order_items')->insert([
                        'order_id' => $order->id,
                        'product_id' => $it['product_id'],
                        'name' => $it['name'],
                        'price' => $it['price'],
                        'quantity' => $it['quantity'],
                        'subtotal' => $it['subtotal'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Fallback: store snapshot
                DB::table('orders')->where('id', $order->id)->update([
                    'items_json' => json_encode($items->values())
                ]);
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');

            $orderNum = str_pad($order->id, 6, '0', STR_PAD_LEFT);
            return redirect()
                ->route('checkout.index')
                ->with('success', "Order #{$orderNum} placed successfully!")
                ->with('placed_order_id', $order->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }
}
