<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function($query) {
            $query->where('is_available', true);
        }])->get();

        return view('mobile.home', compact('categories'));
    }

    public function cart()
    {
        // Cart will typically be managed via Javascript/Alpine.js locally.
        // We just return the view.
        return view('mobile.cart');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:255',
            'payment_method' => 'required|in:take_away,qris',
            'payment_proof' => 'required_if:payment_method,qris|image|mimes:jpeg,png,jpg|max:2048',
            'cart' => 'required|json', // Client sends cart as JSON string
        ]);

        $cartItems = json_decode($request->cart, true);

        if (empty($cartItems)) {
            return back()->with('error', 'Cart is empty');
        }

        // Handle Image Upload
        $paymentProofPath = null;
        if ($request->payment_method === 'qris' && $request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('receipts', 'public');
        }

        try {
            DB::beginTransaction();

            $totalPrice = 0;

            // Calculate daily queue number
            $todayOrdersCount = Order::whereDate('created_at', today())->count();
            $queueNumber = $todayOrdersCount + 1;

            // Prepare order
            $order = Order::create([
                'queue_number' => $queueNumber,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'notes' => $request->notes,
                'total_price' => 0, // Will update
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_proof' => $paymentProofPath,
            ]);

            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return back()->with('error', 'Mohon maaf, stok untuk ' . $product->name . ' tidak mencukupi. (Sisa: ' . $product->stock . ')');
                }

                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return redirect()->route('order.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order. ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        $order->load('items.product');
        return view('mobile.success', compact('order'));
    }
}
