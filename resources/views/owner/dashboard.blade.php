@extends('layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Summary -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-espresso-900 tracking-tight">Dashboard Ringkasan Outlet</h1>
            <p class="text-xs text-amber-900/60 font-semibold mt-1">Selamat datang kembali, <span class="font-bold text-espresso-900">{{ auth()->user()->name }}</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('worker.pos') }}" class="px-4 py-2.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-extrabold rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Buka POS Kasir</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards (4 Cols) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-900/50">Omset Hari Ini</span>
                <span class="p-2.5 rounded-2xl bg-amber-50 text-amber-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="text-xl font-black text-espresso-900 mt-2">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
            <p class="text-[10px] text-amber-900/60 font-semibold mt-1">{{ number_format($todayTransactionsCount) }} Pesanan Selesai</p>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-900/50">Omset Bulan Ini</span>
                <span class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </span>
            </div>
            <p class="text-xl font-black text-emerald-700 mt-2">Rp {{ number_format($monthlySales, 0, ',', '.') }}</p>
            <p class="text-[10px] text-amber-900/60 font-semibold mt-1">Total Penjualan Kotor</p>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-900/50">Pengeluaran Bulan Ini</span>
                <span class="p-2.5 rounded-2xl bg-rose-50 text-rose-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                </span>
            </div>
            <p class="text-xl font-black text-rose-700 mt-2">Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}</p>
            <p class="text-[10px] text-amber-900/60 font-semibold mt-1">Beban Operasional</p>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-900/50">Estimasi Laba Kotor</span>
                <span class="p-2.5 rounded-2xl bg-blue-50 text-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7h6m6 1l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9h-6"/></svg>
                </span>
            </div>
            <p class="text-xl font-black {{ ($monthlySales - $monthlyExpenses) >= 0 ? 'text-espresso-900' : 'text-rose-700' }} mt-2">
                Rp {{ number_format($monthlySales - $monthlyExpenses, 0, ',', '.') }}
            </p>
            <p class="text-[10px] text-amber-900/60 font-semibold mt-1">Omset - Pengeluaran</p>
        </div>
    </div>

    <!-- Grid Konten: Stok Kritis & Top Selling -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Peringatan Stok Bahan Baku Kritis -->
        <div class="bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Stok Bahan Baku Kritis</span>
                </h3>
                <a href="{{ route('owner.ingredients.index') }}" class="text-xs text-latte-600 font-bold hover:underline">Kelola Bahan →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cream-100/50 text-espresso-900 font-bold uppercase border-b border-amber-900/10">
                        <tr>
                            <th class="p-3">Kode</th>
                            <th class="p-3">Nama Bahan</th>
                            <th class="p-3">Sisa Stok</th>
                            <th class="p-3">Min. Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-900/5">
                        @forelse($lowStockIngredients as $ing)
                            <tr class="hover:bg-cream-50/50">
                                <td class="p-3 font-mono text-amber-900/60">{{ $ing->code }}</td>
                                <td class="p-3 font-bold text-espresso-900">{{ $ing->name }}</td>
                                <td class="p-3 font-extrabold text-rose-600">{{ number_format($ing->current_stock, 1) }} {{ $ing->unit }}</td>
                                <td class="p-3 text-amber-900/60 font-semibold">{{ number_format($ing->minimum_stock, 1) }} {{ $ing->unit }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-emerald-700 font-bold">Seluruh stok bahan baku dalam batas aman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Menu Produk Terlaris -->
        <div class="bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-latte-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <span>Top 5 Menu Terlaris</span>
                </h3>
                <a href="{{ route('owner.products.index') }}" class="text-xs text-latte-600 font-bold hover:underline">Lihat Menu →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cream-100/50 text-espresso-900 font-bold uppercase border-b border-amber-900/10">
                        <tr>
                            <th class="p-3">Nama Menu</th>
                            <th class="p-3">Terjual</th>
                            <th class="p-3 text-right">Total Omset</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-900/5">
                        @forelse($topProducts as $top)
                            <tr class="hover:bg-cream-50/50">
                                <td class="p-3 font-bold text-espresso-900">{{ $top->product->name ?? 'Menu' }}</td>
                                <td class="p-3 font-extrabold text-amber-800">{{ number_format($top->total_qty) }} Porsi</td>
                                <td class="p-3 text-right font-black text-emerald-700">Rp {{ number_format($top->total_sales, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-amber-900/40">Belum ada data transaksi penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection