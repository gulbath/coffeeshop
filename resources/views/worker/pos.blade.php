@extends('layouts.app')

@section('title', 'POS Kasir')

@section('content')
<div class="h-[calc(100vh-6rem)] flex flex-col md:flex-row gap-6 -m-4 md:-m-8 p-4 md:p-6 bg-cream-50">

    <!-- Area Kiri: Katalog Produk & Filter -->
    <div class="flex-1 flex flex-col min-w-0 bg-white rounded-3xl border border-amber-900/10 shadow-sm overflow-hidden">
        
        <!-- Filter & Search Bar -->
        <div class="p-4 border-b border-amber-900/10 flex flex-col sm:flex-row gap-3 bg-cream-100/50 backdrop-blur-md">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-900/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="searchInput" onkeyup="filterMenu()" placeholder="Cari nama menu..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-semibold focus:ring-2 focus:ring-latte-500 focus:outline-none bg-white">
            </div>
            
            <div class="flex gap-2 overflow-x-auto pb-1 sm:pb-0">
                <button onclick="filterCategory('all')" class="category-btn active px-4 py-2 rounded-2xl text-xs font-extrabold bg-latte-500 text-white whitespace-nowrap shadow-sm transition-all">
                    Semua Menu
                </button>
                @foreach($categories as $cat)
                    <button onclick="filterCategory('cat-{{ $cat->id }}')" class="category-btn px-4 py-2 rounded-2xl text-xs font-bold bg-cream-100 text-espresso-800 hover:bg-cream-200 whitespace-nowrap transition-colors">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Grid Katalog Produk -->
        <div class="flex-1 p-5 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-max" id="productGrid">
            @forelse($products as $product)
                <div class="product-card cat-{{ $product->category_id }} bg-white p-3.5 rounded-2xl border border-amber-900/10 shadow-sm hover:shadow-md hover:border-latte-500/50 transition-all duration-300 flex flex-col justify-between cursor-pointer group"
                     onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->selling_price }})">
                    
                    <div>
                        <!-- Foto Menu Aspect Square (1:1) Micro-Zoom -->
                        <div class="w-full aspect-square rounded-xl overflow-hidden bg-cream-100 mb-3 border border-amber-900/5 flex items-center justify-center relative">
                            @if($product->image_path && Storage::disk('public')->exists($product->image_path))
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <svg class="w-10 h-10 text-amber-900/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                                </svg>
                            @endif
                            <span class="absolute top-2 left-2 text-[10px] font-extrabold text-espresso-900 bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-lg border border-amber-900/10 shadow-xs">
                                {{ $product->category->name }}
                            </span>
                        </div>

                        <!-- Info Nama Produk -->
                        <h3 class="product-name font-bold text-xs text-espresso-900 line-clamp-2 leading-snug group-hover:text-amber-700 transition-colors">
                            {{ $product->name }}
                        </h3>
                    </div>

                    <!-- Footer Kartu Harga & Tombol Auto (Warna Latte Gold Tegas) -->
                    <div class="mt-3 flex items-center justify-between border-t border-amber-900/5 pt-2.5">
                        <span class="font-black text-xs text-emerald-700">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                        <span class="px-2.5 py-1 rounded-xl bg-latte-500 hover:bg-amber-600 text-white flex items-center justify-center font-extrabold text-[10px] shadow-sm gap-1 transition-colors">
                            <span>+</span> <span>Auto</span>
                        </span>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center text-amber-900/40 text-xs font-semibold">
                    Belum ada menu produk aktif yang dapat dijual.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Area Kanan: Ringkasan Pesanan & Pembayaran -->
    <div class="w-full md:w-96 bg-white rounded-3xl border border-amber-900/10 shadow-sm flex flex-col h-full overflow-hidden">
        <div class="p-4 border-b border-amber-900/10 bg-cream-100/50 flex items-center justify-between">
            <h2 class="font-extrabold text-xs text-espresso-900 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-latte-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Keranjang Pesanan</span>
            </h2>
            <button onclick="clearCart()" class="text-xs text-rose-600 hover:text-rose-800 font-bold transition-colors">Kosongkan</button>
        </div>

        <!-- List Item Keranjang -->
        <div class="flex-1 p-4 overflow-y-auto space-y-2.5" id="cartContainer">
            <p class="text-xs text-amber-900/40 text-center py-16 font-semibold" id="emptyCartText">Keranjang belanja masih kosong.</p>
        </div>

        <!-- Panel Pembayaran Multi-Method -->
        <div class="p-4 border-t border-amber-900/10 bg-cream-100/50 space-y-3">
            
            <!-- Input Diskon -->
            <div>
                <label for="discountInput" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Diskon Potongan (Rp)</label>
                <input type="number" id="discountInput" oninput="renderCart()" placeholder="0" 
                       class="w-full px-3.5 py-2 rounded-xl border border-amber-900/10 text-xs font-bold focus:ring-2 focus:ring-latte-500 focus:outline-none bg-white">
            </div>

            <div class="space-y-1 text-xs">
                <div class="flex justify-between text-amber-900/60 font-semibold">
                    <span>Subtotal:</span>
                    <span id="subtotalDisplay">Rp 0</span>
                </div>
                <div class="flex justify-between font-extrabold text-espresso-900">
                    <span>Total Final:</span>
                    <span class="text-base font-black text-emerald-700" id="totalDisplay">Rp 0</span>
                </div>
            </div>

            <!-- Tombol Metode Pembayaran -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Metode Pembayaran</label>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="setPaymentMethod('cash')" id="btnMethodCash" class="payment-method-btn active py-2 rounded-xl text-xs font-bold bg-latte-500 text-white border border-latte-500 shadow-sm flex items-center justify-center gap-1.5 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Tunai</span>
                    </button>
                    <button type="button" onclick="setPaymentMethod('qris')" id="btnMethodQris" class="payment-method-btn py-2 rounded-xl text-xs font-bold bg-white text-espresso-900 border border-amber-900/10 hover:border-amber-900/30 flex items-center justify-center gap-1.5 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>QRIS</span>
                    </button>
                    <button type="button" onclick="setPaymentMethod('debit')" id="btnMethodDebit" class="payment-method-btn py-2 rounded-xl text-xs font-bold bg-white text-espresso-900 border border-amber-900/10 hover:border-amber-900/30 flex items-center justify-center gap-1.5 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Debit</span>
                    </button>
                </div>
            </div>

            <!-- METODE TUNAI -->
            <div id="cashInputContainer" class="space-y-2">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60">Nominal Uang Tunai</label>
                
                <div class="grid grid-cols-4 gap-1.5">
                    <button type="button" onclick="setQuickCash('pas')" class="py-1 px-2 bg-cream-200 hover:bg-cream-300 rounded-lg text-[10px] font-extrabold text-espresso-900 transition-colors">Uang Pas</button>
                    <button type="button" onclick="setQuickCash(20000)" class="py-1 px-2 bg-cream-200 hover:bg-cream-300 rounded-lg text-[10px] font-extrabold text-espresso-900 transition-colors">20rb</button>
                    <button type="button" onclick="setQuickCash(50000)" class="py-1 px-2 bg-cream-200 hover:bg-cream-300 rounded-lg text-[10px] font-extrabold text-espresso-900 transition-colors">50rb</button>
                    <button type="button" onclick="setQuickCash(100000)" class="py-1 px-2 bg-cream-200 hover:bg-cream-300 rounded-lg text-[10px] font-extrabold text-espresso-900 transition-colors">100rb</button>
                </div>

                <input type="number" id="cashInput" oninput="calculateChange()" placeholder="0" 
                       class="w-full px-3.5 py-2 rounded-xl border border-amber-900/10 text-xs font-bold focus:ring-2 focus:ring-latte-500 focus:outline-none bg-white">
                
                <div class="flex justify-between text-[11px] font-extrabold mt-1 text-espresso-900">
                    <span>Kembalian:</span>
                    <span id="changeDisplay" class="text-blue-700">Rp 0</span>
                </div>
            </div>

            <!-- METODE QRIS -->
            <div id="qrisContainer" class="hidden p-4 bg-white rounded-2xl border border-amber-900/10 text-center space-y-2 shadow-xs ring-2 ring-latte-500/30">
                <div class="flex items-center justify-center gap-1.5 font-extrabold text-xs text-amber-900">
                    <svg class="w-4 h-4 text-latte-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>Scan QRIS Pembayaran</span>
                </div>
                
                <div class="bg-cream-50 p-3 rounded-2xl border border-amber-900/10 inline-block shadow-inner">
                    <img id="qrisImage" src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=COFFEESHOP-QRIS" 
                         alt="QRIS Code" class="w-40 h-40 object-contain mx-auto rounded-lg">
                </div>

                <p class="text-[10px] text-amber-900/60 font-semibold">Scan via m-Banking atau E-Wallet</p>
                <p class="text-[11px] text-espresso-900 font-extrabold">Nominal Tagihan: <span id="qrisTotalText" class="font-black text-emerald-700">Rp 0</span></p>
            </div>

            <!-- METODE DEBIT -->
            <div id="debitContainer" class="hidden p-4 bg-white rounded-2xl border border-amber-900/10 text-center space-y-2 shadow-xs border-blue-200">
                <div class="flex items-center justify-center gap-1.5 font-extrabold text-xs text-blue-900">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Mesin EDC / Debit</span>
                </div>
                <div class="py-2 text-blue-700 flex justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
                <p class="text-[11px] font-bold text-espresso-900">Gesek atau Masukkan Kartu ke Mesin EDC</p>
                <p class="text-[10px] text-blue-700 font-semibold">Nominal EDC: <span id="debitTotalText" class="font-black text-emerald-700">Rp 0</span></p>
            </div>

            <button onclick="submitOrder()" id="btnSubmitOrder" class="w-full py-3.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-black rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all active:scale-98">
                Bayar & Selesaikan Pesanan
            </button>
        </div>
    </div>

</div>

<!-- Script JavaScript POS -->
<script>
    let cart = [];
    let selectedMethod = 'cash';

    function addToCart(id, name, price) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.qty++;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (item) {
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
        }
        renderCart();
    }

    function clearCart() {
        cart = [];
        document.getElementById('discountInput').value = '';
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartContainer');
        if (cart.length === 0) {
            container.innerHTML = '<p class="text-xs text-amber-900/40 text-center py-16 font-semibold">Keranjang belanja masih kosong.</p>';
            document.getElementById('subtotalDisplay').innerText = 'Rp 0';
            document.getElementById('totalDisplay').innerText = 'Rp 0';
            updatePaymentContainers(0);
            return;
        }

        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            const itemSubtotal = item.price * item.qty;
            subtotal += itemSubtotal;
            html += `
                <div class="p-3 bg-white rounded-2xl border border-amber-900/10 shadow-xs flex items-center justify-between">
                    <div class="min-w-0 flex-1 pr-2">
                        <h4 class="text-xs font-bold text-espresso-900 truncate">${item.name}</h4>
                        <p class="text-[10px] text-amber-900/50">Rp ${item.price.toLocaleString('id-ID')} x ${item.qty}</p>
                        <p class="text-xs font-extrabold text-emerald-700">Rp ${itemSubtotal.toLocaleString('id-ID')}</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button onclick="updateQty(${item.id}, -1)" class="w-6 h-6 rounded-lg bg-cream-200 font-bold text-xs hover:bg-cream-300 transition-colors">-</button>
                        <span class="text-xs font-extrabold px-1">${item.qty}</span>
                        <button onclick="updateQty(${item.id}, 1)" class="w-6 h-6 rounded-lg bg-latte-500 text-white font-bold text-xs hover:bg-amber-600 transition-colors">+</button>
                    </div>
                </div>
            `;
        });

        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const totalFinal = Math.max(0, subtotal - discount);

        container.innerHTML = html;
        document.getElementById('subtotalDisplay').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('totalDisplay').innerText = 'Rp ' + totalFinal.toLocaleString('id-ID');
        
        updatePaymentContainers(totalFinal);
    }

    function updatePaymentContainers(totalFinal) {
        document.getElementById('qrisTotalText').innerText = 'Rp ' + totalFinal.toLocaleString('id-ID');
        document.getElementById('debitTotalText').innerText = 'Rp ' + totalFinal.toLocaleString('id-ID');

        const qrisData = encodeURIComponent(`COFFEESHOP-PAYMENT-RP-${totalFinal}`);
        document.getElementById('qrisImage').src = `https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${qrisData}`;

        calculateChange();
    }

    function setQuickCash(amount) {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const totalFinal = Math.max(0, subtotal - discount);

        if (amount === 'pas') {
            document.getElementById('cashInput').value = totalFinal;
        } else {
            document.getElementById('cashInput').value = amount;
        }
        calculateChange();
    }

    function setPaymentMethod(method) {
        selectedMethod = method;

        document.querySelectorAll('.payment-method-btn').forEach(btn => {
            btn.classList.remove('bg-latte-500', 'text-white', 'border-latte-500');
            btn.classList.add('bg-white', 'text-espresso-900', 'border-amber-900/10');
        });

        const activeBtn = document.getElementById('btnMethod' + method.charAt(0).toUpperCase() + method.slice(1));
        activeBtn.classList.remove('bg-white', 'text-espresso-900', 'border-amber-900/10');
        activeBtn.classList.add('bg-latte-500', 'text-white', 'border-latte-500');

        const cashBox = document.getElementById('cashInputContainer');
        const qrisBox = document.getElementById('qrisContainer');
        const debitBox = document.getElementById('debitContainer');

        cashBox.classList.add('hidden');
        qrisBox.classList.add('hidden');
        debitBox.classList.add('hidden');

        if (method === 'cash') {
            cashBox.classList.remove('hidden');
        } else if (method === 'qris') {
            qrisBox.classList.remove('hidden');
        } else if (method === 'debit') {
            debitBox.classList.remove('hidden');
        }
    }

    function calculateChange() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const totalFinal = Math.max(0, subtotal - discount);

        const cash = parseFloat(document.getElementById('cashInput').value) || 0;
        const change = Math.max(0, cash - totalFinal);
        document.getElementById('changeDisplay').innerText = 'Rp ' + change.toLocaleString('id-ID');
    }

    function filterMenu() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            const name = card.querySelector('.product-name').innerText.toLowerCase();
            card.style.display = name.includes(query) ? 'flex' : 'none';
        });
    }

    function filterCategory(catClass) {
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('bg-latte-500', 'text-white');
            btn.classList.add('bg-cream-100', 'text-espresso-800');
        });
        event.target.classList.remove('bg-cream-100', 'text-espresso-800');
        event.target.classList.add('bg-latte-500', 'text-white');

        document.querySelectorAll('.product-card').forEach(card => {
            if (catClass === 'all' || card.classList.contains(catClass)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    async function submitOrder() {
        if (cart.length === 0) {
            alert('Keranjang belanja masih kosong!');
            return;
        }

        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const totalFinal = Math.max(0, subtotal - discount);
        const cash = parseFloat(document.getElementById('cashInput').value) || 0;

        if (selectedMethod === 'cash' && cash < totalFinal) {
            alert('Uang tunai yang dimasukkan kurang dari total belanja.');
            return;
        }

        const btn = document.getElementById('btnSubmitOrder');
        btn.disabled = true;
        btn.innerText = 'Memproses...';

        try {
            const response = await fetch("{{ route('worker.pos.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    items: cart,
                    discount_amount: discount,
                    payment_method: selectedMethod,
                    cash_amount: selectedMethod === 'cash' ? cash : totalFinal
                })
            });

            const result = await response.json();

            if (result.success) {
                alert(`Transaksi ${selectedMethod.toUpperCase()} Berhasil!\n\nNo: ${result.transaction_number}\nTotal: Rp ${result.total_amount.toLocaleString('id-ID')}\nKembalian: Rp ${result.change_amount.toLocaleString('id-ID')}`);
                cart = [];
                document.getElementById('discountInput').value = '';
                document.getElementById('cashInput').value = '';
                renderCart();
            } else {
                alert('Transaksi Gagal: ' + result.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Bayar & Selesaikan Pesanan';
        }
    }
</script>
@endsection