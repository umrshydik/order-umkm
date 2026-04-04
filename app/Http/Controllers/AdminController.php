<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view pesanan', only: ['orders']),
            new Middleware('permission:edit pesanan', only: ['updateOrderStatus']),
        ];
    }
    public function dashboard()
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        return view('admin.dashboard', compact('todayOrders', 'pendingOrders', 'totalRevenue'));
    }

    public function orders()
    {
        $orders = Order::whereDate('created_at', today())->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully');
    }
}
