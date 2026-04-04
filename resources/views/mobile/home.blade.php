@extends('layouts.mobile')

@section('title', config('app.name', 'Menu UMKM'))

@section('content')
    <!-- Banner -->
    <div class="mt-4 mb-6 rounded-xl overflow-hidden shadow-sm">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=600&auto=format&fit=crop" alt="Promo Banner" class="w-full h-40 object-cover">
    </div>

    <!-- Categories Tab Bar (Horizontal Scroll) -->
    <div class="flex overflow-x-auto space-x-4 mb-6 hide-scrollbar pb-2 pt-2 -mx-4 px-4 sticky top-16 bg-white z-40 border-b">
        @foreach($categories as $category)
            <a href="#category-{{ $category->id }}" class="whitespace-nowrap px-4 py-2 bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-full text-sm font-semibold transition-colors">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- Products List by Category -->
    <div class="pb-16 section-list">
        @foreach($categories as $category)
            <div id="category-{{ $category->id }}" class="mb-8 pt-4">
                <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $category->name }}</h2>
                
                <div class="grid grid-cols-1 gap-4">
                    @foreach($category->products as $product)
                        <div class="bg-white rounded-xl p-4 shadow-sm flex items-center border border-gray-100">
                            <!-- Product Image -->
                            <div class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover {{ $product->stock <= 0 ? 'grayscale' : '' }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=200&auto=format&fit=crop" alt="Food Placeholder" class="w-full h-full object-cover {{ $product->stock <= 0 ? 'grayscale' : '' }}">
                                @endif
                            </div>
                            
                            <!-- Product Detail -->
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                                <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $product->description }}</p>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="font-bold text-red-600">Rp <span x-text="formatRupiah({{ $product->price }})"></span></span>
                                    
                                    @if($product->stock > 0)
                                        <!-- Add button -->
                                        <button @click="addToCart({{ $product->toJson() }})" class="bg-red-100 text-red-600 hover:bg-red-600 hover:text-white rounded-full p-2 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    @else
                                        <span class="text-sm font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($category->products->isEmpty())
                        <p class="text-sm text-gray-400 italic">Belum ada menu di kategori ini.</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
