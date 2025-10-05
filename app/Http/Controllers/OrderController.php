<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.dashboard.orders', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'payment', 'delivery']);
        
        return view('admin.dashboard.order-details', compact('order'));
    }

    /**
     * Update the order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.dashboard.orders')
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Update delivery status
     */
    public function updateDeliveryStatus(Request $request, Order $order)
    {
        $request->validate([
            'delivery_status' => 'required|in:pending,shipped,delivered'
        ]);

        $order->delivery()->updateOrCreate(
            ['order_id' => $order->id],
            ['status' => $request->delivery_status]
        );

        return redirect()->route('admin.dashboard.orders')
            ->with('success', 'Delivery status updated successfully.');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            ['status' => $request->payment_status]
        );

        return redirect()->route('admin.dashboard.orders')
            ->with('success', 'Payment status updated successfully.');
    }

    /**
     * Get orders statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
        ];

        $recentOrders = Order::with(['user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard.order-stats', compact('stats', 'recentOrders'));
    }

    /**
     * Get orders by status
     */
    public function getByStatus($status)
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.dashboard.orders', compact('orders', 'status'));
    }

    /**
     * Search orders
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $orders = Order::with(['user', 'orderItems.product'])
            ->whereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->orWhere('id', 'like', "%{$query}%")
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.dashboard.orders', compact('orders', 'query'));
    }

    /**
     * Display customer orders
     */
    public function customerOrders(Request $request)
    {
        if (!auth()->check()) {
            return view('orders', ['orders' => collect(), 'isGuest' => true]);
        }

        $status = $request->get('status', 'all');
        
        $query = Order::with(['orderItems.product'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->get();

        return view('orders', compact('orders', 'status'));
    }

    /**
     * Add order items to cart (Buy Again functionality)
     */
    public function buyAgain(Order $order)
    {
        if (!auth()->check() || $order->user_id !== auth()->id()) {
            return redirect()->route('orders')->with('error', 'Unauthorized access.');
        }

        $cart = session()->get('cart', []);
        
        foreach ($order->orderItems as $item) {
            $productId = $item->product_id;
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $item->quantity;
            } else {
                $cart[$productId] = [
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'image' => $item->product->image_path
                ];
            }
        }
        
        session()->put('cart', $cart);
        
        return redirect()->route('cart.index')->with('success', 'Items added to cart successfully!');
    }
}