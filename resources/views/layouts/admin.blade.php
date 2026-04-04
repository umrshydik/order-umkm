<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Order Food UMKM') }} Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md hidden sm:block">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-bold text-gray-800">Admin Panel</h2>
        </div>
        <nav class="mt-6 flex flex-col gap-2 px-4">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Dashboard</a>
            
            @can('view pesanan')
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Pesanan</a>
            @endcan

            <hr class="my-2 border-gray-200">

            @can('view kategori')
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Kategori</a>
            @endcan
            
            @can('view produk')
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Produk</a>
            @endcan


            @can('view pendapatan')
            <hr class="my-2 border-gray-200">

            <a href="{{ route('admin.incomes.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Pendapatan</a>
            @endcan
            
            <hr class="my-2 border-gray-200">
            
            @can('view pengguna')
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Pengguna</a>
            @endcan
            
            @can('view hak akses')
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Hak Akses</a>
            @endcan
            
            @can('view permission')
            <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Permissions</a>
            @endcan
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 pb-12">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">
                    @yield('header', 'Dashboard Admin')
                </h1>
                
                @auth
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">Halo, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900 border border-red-200 px-3 py-1 rounded hover:bg-red-50">Logout</button>
                    </form>
                </div>
                @endauth
            </div>
        </header>

        <!-- Content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
