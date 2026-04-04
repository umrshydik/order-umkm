@extends('layouts.admin')

@section('header', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Pesanan Hari Ini</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $todayOrders }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Menunggu (Pending)</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingOrders }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Pendapatan Total</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Selamat Datang di Admin Panel</h3>
    <p class="text-gray-600">Gunakan menu di sebelah kiri untuk mengelola kategori, produk, dan memantau pesanan masuk secara real-time.</p>
</div>
@endsection
