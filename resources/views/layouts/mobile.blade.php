<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name', 'Order UMKM') }}</title>
    <!-- Use CDN for Tailwind since composer/npm is not available locally -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine JS for cart state management -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Hide scrollbar for category menu */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-200 text-gray-800 antialiased font-sans">
    
    <!-- Mobile App Container -->
    <div x-data="cartStore()" class="max-w-md mx-auto bg-white min-h-screen relative shadow-2xl flex flex-col pt-16 pb-24">
        
        <!-- Header -->
        <header class="fixed top-0 w-full max-w-md bg-white shadow-sm z-50 h-16 flex items-center justify-center px-4">
            <h1 class="text-xl font-bold text-red-600">@yield('title', 'Menu UMKM')</h1>
            @hasSection('back')
                <div class="absolute left-4">
                    @yield('back')
                </div>
            @endif
        </header>

        <!-- Main Content -->
        <main class="flex-grow overflow-y-auto hide-scrollbar px-4">
            @yield('content')
        </main>

        <!-- Bottom Cart Bar (Sticky if items in cart) -->
        <div x-show="totalItems > 0 && !isCheckoutPage()" x-cloak 
             class="fixed bottom-0 w-full max-w-md bg-white border-t p-4 z-50">
            <a href="{{ route('cart') }}" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg flex justify-between items-center transition-transform active:scale-95">
                <div class="flex flex-col">
                    <span class="text-sm font-medium"><span x-text="totalItems"></span> Item</span>
                    <span class="text-lg font-bold">Rp <span x-text="formatRupiah(totalPrice)"></span></span>
                </div>
                <div class="flex items-center space-x-2">
                    <span>Lihat Keranjang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
        </div>

        <!-- Toast Notification -->
        <div x-show="toastMessage" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-10"
             class="fixed bottom-24 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-6 py-3 rounded-full shadow-lg z-[100] flex items-center justify-center whitespace-nowrap" style="display: none;">
            <span x-text="toastMessage" class="text-sm font-medium"></span>
        </div>
    </div>

    <!-- Alpine.js Store Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartStore', () => ({
                cart: JSON.parse(localStorage.getItem('umkm_cart') || '[]'),
                toastMessage: null,
                toastTimeout: null,
                
                showToast(msg) {
                    this.toastMessage = msg;
                    if (this.toastTimeout) clearTimeout(this.toastTimeout);
                    this.toastTimeout = setTimeout(() => {
                        this.toastMessage = null;
                    }, 3000);
                },

                get totalItems() {
                    return this.cart.reduce((total, item) => total + item.quantity, 0);
                },
                
                get totalPrice() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                isCheckoutPage() {
                    return window.location.pathname.includes('/cart') || window.location.pathname.includes('/success');
                },

                addToCart(product) {
                    const existing = this.cart.find(i => i.id === product.id);
                    if (existing) {
                        if (existing.quantity >= product.stock) {
                            this.showToast('Stok tidak mencukupi');
                            return;
                        }
                        existing.quantity++;
                    } else {
                        if (product.stock <= 0) {
                            this.showToast('Stok habis');
                            return;
                        }
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            image: product.image,
                            stock: product.stock,
                            quantity: 1
                        });
                    }
                    this.saveCart();
                    this.showToast('Ditambahkan ke keranjang');
                },

                removeFromCart(productId) {
                    this.cart = this.cart.filter(item => item.id !== productId);
                    this.saveCart();
                },

                updateQuantity(productId, type) {
                    const item = this.cart.find(i => i.id === productId);
                    if (item) {
                        if (type === 'inc') {
                            if (item.quantity >= item.stock) {
                                this.showToast('Stok tidak mencukupi');
                                return;
                            }
                            item.quantity++;
                        } else if (type === 'dec') {
                            if (item.quantity > 1) {
                                item.quantity--;
                            } else {
                                this.removeFromCart(productId);
                                return;
                            }
                        }
                    }
                    this.saveCart();
                },

                saveCart() {
                    localStorage.setItem('umkm_cart', JSON.stringify(this.cart));
                },

                clearCart() {
                    this.cart = [];
                    this.saveCart();
                },

                formatRupiah(angka) {
                    var rupiah = '';		
                    var angkarev = angka.toString().split('').reverse().join('');
                    for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
                    return rupiah.split('',rupiah.length-1).reverse().join('');
                }
            }));
        });
    </script>
</body>
</html>
