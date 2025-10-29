<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Show cart page
    public function index(Request $request)
    {
        $cart = session('cart', []);
        $items = collect($cart)->map(function ($item) {
            $item['subtotal'] = $item['price'] * $item['quantity'];
            return $item;
        });
        $total = $items->sum('subtotal');

        return view('cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    // Add product to cart
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $quantity = (int) ($request->input('quantity', 1));

        $cart = session('cart', []);
        $key = (string) $product->id;

        if (!isset($cart[$key])) {
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->dish_name,
                'price' => (float) $product->price,
                'image' => $product->image_path,
                'quantity' => 0,
            ];
        }

        $cart[$key]['quantity'] = min(99, $cart[$key]['quantity'] + $quantity);

        session(['cart' => $cart]);

        if ($request->input('redirect') === 'checkout') {
            return redirect()->route('checkout.index')->with('success', 'Added to cart. Proceed to checkout.');
        }

        return back()->with('success', 'Added to cart.');
    }

    // Update quantity
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $cart = session('cart', []);
        $key = (string) $product->id;

        if (!isset($cart[$key])) {
            return back()->with('error', 'Item not found in cart.');
        }

        if ((int) $data['quantity'] === 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['quantity'] = (int) $data['quantity'];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Cart updated.');
    }

    // Remove item
    public function remove(Request $request, Product $product)
    {
        $cart = session('cart', []);
        $key = (string) $product->id;
        unset($cart[$key]);
        session(['cart' => $cart]);
        return back()->with('success', 'Item removed.');
    }

    // Clear cart
    public function clear(Request $request)
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }
}
