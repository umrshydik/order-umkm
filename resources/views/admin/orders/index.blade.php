@extends('layouts.admin')

@section('header', 'Kelola Pesanan')

@section('content')
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Pesanan Masuk</h3>
    </div>
    <ul class="divide-y divide-gray-200 p-4 space-y-4">
        @foreach($orders as $order)
            <li class="bg-white border rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Antrean #{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}</span>
                        <span class="ml-2 text-sm text-gray-400">• {{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    <div>
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <select name="status" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif
                            " onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </form>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $order->customer_name }}</p>
                        <p class="text-xs text-gray-500"><svg class="w-3 h-3 inline pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> {{ $order->customer_phone }}</p>
                        <p class="text-xs text-gray-500 mt-1">Meja: {{ $order->table_number ?: '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-red-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Item Pesanan:</h4>
                    <ul class="space-y-1">
                        @foreach($order->items as $item)
                        <li class="flex justify-between text-sm">
                            <span class="text-gray-700">{{ $item->quantity }}x {{ $item->product ? $item->product->name : 'Produk Dihapus' }}</span>
                            <span class="text-gray-500">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @endforeach
        
        @if($orders->isEmpty())
            <li class="text-center py-8 text-gray-500">Belum ada pesanan.</li>
        @endif
    </ul>
    
    <div class="px-6 py-4 border-t">
        {{ $orders->links() }}
    </div>
</div>
@endsection
