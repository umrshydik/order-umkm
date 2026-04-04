@extends('layouts.mobile')

@section('title', 'Pesanan Diterima')

@section('content')
    <div class="flex flex-col items-center justify-center py-12 px-6 text-center h-full">
        <!-- Success Animation/Icon -->
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6 shadow-sm border border-green-200">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Terima Kasih!</h1>
        <p class="text-gray-600 mb-8">Pesanan Anda berhasil dibuat dan sedang kami siapkan.</p>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full max-w-sm mb-8 relative overflow-hidden">
            <!-- Decorative ticket indentations -->
            <div class="absolute -left-3 top-1/2 w-6 h-6 bg-gray-200 rounded-full"></div>
            <div class="absolute -right-3 top-1/2 w-6 h-6 bg-gray-200 rounded-full"></div>

            <div class="border-b border-dashed border-gray-300 pb-4 mb-4">
                <p class="text-sm text-gray-500 uppercase tracking-widest font-semibold mb-1">ID Pesanan</p>
                <p class="text-xl font-bold text-gray-800">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="space-y-3 text-left">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Nama Pemesan</span>
                    <span class="font-medium text-gray-800">{{ $order->customer_name }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">No. Meja</span>
                    <span class="font-medium text-gray-800">{{ $order->table_number ?: '-' }}</span>
                </div>
                <div class="flex justify-between items-center text-sm pt-2">
                    <span class="font-medium text-gray-800">Total Pembayaran</span>
                    <span class="font-bold text-red-600 text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <a href="{{ route('home') }}" class="w-full max-w-sm bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-colors active:scale-95 flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Menu</span>
        </a>
    </div>
@endsection
