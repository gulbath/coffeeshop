<aside class="w-full md:w-64 bg-espresso-800 text-cream-100 flex flex-col flex-shrink-0 border-r border-espresso-700/50 min-h-screen">
    <!-- Brand Header -->
    <div class="p-6 flex items-center gap-3 border-b border-espresso-700/50">
        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-latte-500 to-amber-700 text-white flex items-center justify-center font-black shadow-lg shadow-amber-900/20">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
            </svg>
        </div>
        <div>
            <h1 class="font-extrabold text-base text-cream-50 tracking-tight leading-none">CoffeeShop</h1>
            <p class="text-[10px] text-latte-500 font-bold uppercase tracking-widest mt-1">Artisan System</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        @if(auth()->user()->isOwner())
            <div class="px-3 text-[10px] font-bold uppercase tracking-widest text-amber-200/40 mb-2 mt-1">Owner Portal</div>
            
            <a href="{{ route('owner.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Dashboard Overview</span>
            </a>

            <a href="{{ route('owner.categories.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('owner.categories.*') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>Kategori Produk</span>
            </a>

            <a href="{{ route('owner.products.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('owner.products.*') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>
                <span>Menu & Produk</span>
            </a>

            <a href="{{ route('owner.ingredients.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('owner.ingredients.*') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Bahan Baku & Resep</span>
            </a>

            <a href="{{ route('owner.inventory.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('owner.inventory.*') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Stok & Inventory</span>
            </a>

            <a href="{{ route('owner.workers.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('owner.workers.*') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Kelola Staf & Kasir</span>
            </a>

            <a href="{{ route('owner.financials.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('owner.financials.*') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Arus Kas (Multi-Panel)</span>
            </a>

            <a href="{{ route('owner.reports.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('owner.reports.*') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Laporan Keuangan</span>
            </a>

            <div class="px-3 text-[10px] font-bold uppercase tracking-widest text-amber-200/40 mt-5 mb-2">Kasir Portal</div>
            
            <a href="{{ route('worker.pos') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('worker.pos') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>POS Kasir</span>
            </a>

            <a href="{{ route('worker.history') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('worker.history') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Riwayat Transaksi</span>
            </a>
        @else
            <div class="px-3 text-[10px] font-bold uppercase tracking-widest text-amber-200/40 mb-2">Worker Portal</div>
            <a href="{{ route('worker.pos') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('worker.pos') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>POS Kasir</span>
            </a>
            <a href="{{ route('worker.history') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('worker.history') ? 'bg-latte-500 text-white font-bold shadow-md shadow-amber-900/30' : 'text-cream-200/80 hover:bg-espresso-700/60 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Riwayat Transaksi</span>
            </a>
        @endif
    </nav>

    <!-- Footer Profile -->
    <div class="p-4 border-t border-espresso-700/50 bg-espresso-900/40">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-xl bg-latte-500 text-white flex items-center justify-center font-black text-xs uppercase shadow-sm">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-cream-50 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-latte-500 capitalize font-medium">{{ auth()->user()->role }}</p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-cream-300/70 hover:text-rose-400 p-2 rounded-xl transition-colors hover:bg-espresso-700/50" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>