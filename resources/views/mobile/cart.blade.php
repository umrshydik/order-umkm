@extends('layouts.mobile')

@section('title', 'Keranjang Pesanan')

@section('back')
<a href="{{ route('home') }}" class="p-2 -ml-2 text-gray-600 hover:text-red-600">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
</a>
@endsection

@section('content')
    <div class="pt-6 pb-24" x-data="{
        form: { name: '', phone: '', notes: '', payment_method: 'take_away', payment_proof: null }
    }">
        @if(session('error'))
            <div class="mx-4 mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        <template x-if="cart.length === 0">
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <svg class="w-20 h-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <h2 class="text-xl font-bold text-gray-700">Keranjang Kosong</h2>
                <p class="text-sm text-gray-500 mt-2 mb-6">Yuk, pilih menu makanan terlebih dahulu.</p>
                <a href="{{ route('home') }}" class="px-6 py-2 bg-red-600 text-white rounded-full font-semibold">Lihat Menu</a>
            </div>
        </template>

        <template x-if="cart.length > 0">
            <div>
                <!-- Cart Items List -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <h3 class="font-bold border-b pb-3 mb-3 text-gray-800">Daftar Pesanan</h3>
                    <div class="space-y-4">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 text-sm" x-text="item.name"></h4>
                                    <p class="text-xs text-red-600 font-medium">Rp <span x-text="formatRupiah(item.price)"></span></p>
                                </div>
                                
                                <!-- Stepper -->
                                <div class="flex items-center space-x-3 bg-gray-50 border rounded-full px-2 py-1">
                                    <button @click="updateQuantity(item.id, 'dec')" class="text-gray-500 hover:text-red-600 p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </button>
                                    <span class="text-sm font-semibold w-4 text-center" x-text="item.quantity"></span>
                                    <button @click="updateQuantity(item.id, 'inc')" :class="{'opacity-30 cursor-not-allowed': item.quantity >= item.stock}" class="text-gray-500 hover:text-green-600 p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="border-t mt-4 pt-3 flex justify-between items-center text-lg font-bold">
                        <span>Total (Excl. Tax)</span>
                        <span class="text-red-600">Rp <span x-text="formatRupiah(totalPrice)"></span></span>
                    </div>
                </div>

                <!-- Customer Form & Checkout -->
                <form action="{{ route('checkout') }}" method="POST" id="checkout-form" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="cart" x-bind:value="JSON.stringify(cart)">
                    
                    <h3 class="font-bold border-b pb-3 mb-4 text-gray-800">Detail Pemesan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan *</label>
                            <input type="text" name="customer_name" required x-model="form.name" class="w-full px-4 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Misal: Budi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp / HP *</label>
                            <input type="tel" name="customer_phone" required x-model="form.phone" class="w-full px-4 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Misal: 08123456789">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                            <input type="text" name="notes" x-model="form.notes" class="w-full px-4 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Misal: Pedas, Tidak pakai bawang, dsb">
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="border rounded-lg p-3 flex items-center cursor-pointer transition-colors" :class="form.payment_method === 'take_away' ? 'bg-red-50 border-red-500 ring-1 ring-red-500' : 'border-gray-200 hover:bg-gray-50'">
                                    <input type="radio" name="payment_method" value="take_away" x-model="form.payment_method" class="sr-only">
                                    <span class="text-sm font-medium" :class="form.payment_method === 'take_away' ? 'text-red-700' : 'text-gray-700'">Take Away</span>
                                </label>
                                <label class="border rounded-lg p-3 flex items-center cursor-pointer transition-colors" :class="form.payment_method === 'qris' ? 'bg-red-50 border-red-500 ring-1 ring-red-500' : 'border-gray-200 hover:bg-gray-50'">
                                    <input type="radio" name="payment_method" value="qris" x-model="form.payment_method" class="sr-only">
                                    <span class="text-sm font-medium" :class="form.payment_method === 'qris' ? 'text-red-700' : 'text-gray-700'">Bayar pakai QRIS</span>
                                </label>
                            </div>
                        </div>

                        <!-- QRIS Upload Section -->
                        <div x-show="form.payment_method === 'qris'" x-transition class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200 text-center">
                            <p class="text-sm text-gray-600 mb-3">Silakan scan QRIS berikut untuk melakukan pembayaran:</p>
                            <img src="{{ asset('images/qris-tempe.jpeg') }}" alt="QRIS" class="w-48 h-48 object-cover rounded-lg mx-auto shadow-sm mb-4 border">
                            
                            <div class="text-left">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pay/Transfer *</label>
                                <input type="file" name="payment_proof" accept="image/*" @change="form.payment_proof = $event.target.files[0]" :required="form.payment_method === 'qris'" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 p-2 border border-gray-200 rounded-xl bg-white focus:ring-red-500 focus:border-red-500">
                                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, ukuran maks: 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Fixed Bottom Checkout Button -->
                    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-white border-t p-4 z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                        <button type="button" @click="$el.closest('form').reportValidity() ? ($el.closest('form').submit(), clearCart()) : null" :disabled="!form.name || !form.phone || (form.payment_method === 'qris' && !form.payment_proof)" 
                                class="w-full bg-red-600 hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-colors flex justify-between items-center">
                            <span>Pesan Sekarang</span>
                            <span x-text="'Rp ' + formatRupiah(totalPrice)"></span>
                        </button>
                    </div>
                </form>
            </div>
        </template>
    </div>
@endsection
