@extends('layouts.app')

@section('title', 'Laporan Keuangan & Laba Rugi')

@section('content')
<div class="space-y-6">
    <!-- Header & Cetak Laporan -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-espresso-900 tracking-tight">Laporan Keuangan & Laba Rugi</h1>
            <p class="text-xs text-amber-900/60 font-semibold mt-1">Ringkasan performa finansial coffee shop Anda.</p>
        </div>
        <div>
            <button onclick="window.print()" class="px-4 py-2.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-extrabold rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- Filter Periode Tanggal -->
    <div class="bg-white p-4 rounded-3xl border border-amber-900/10 shadow-xs">
        <form method="GET" action="{{ route('owner.reports.index') }}" class="flex flex-col sm:flex-row items-end gap-3">
            <div>
                <label for="start_date" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? date('Y-m-01') }}"
                       class="px-3.5 py-2 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>
            <div>
                <label for="end_date" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? date('Y-m-t') }}"
                       class="px-3.5 py-2 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>
            <button type="submit" class="px-4 py-2 bg-latte-500 hover:bg-amber-600 text-white font-extrabold rounded-2xl text-xs transition-colors shadow-xs">
                Tampilkan Laporan
            </button>
        </form>
    </div>

    <!-- Stat Cards Ringkasan Laporan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-amber-900/10 shadow-xs">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-900/50">Total Penjualan Kotor</span>
            <p class="text-xl font-black text-espresso-900 mt-2">Rp {{ number_format($grossSales ?? 0, 0, ',', '.') }}</p>
            <p class="text-[10px] text-amber-900/60 font-semibold mt-1">{{ number_format($totalTransactions ?? 0) }} Transaksi Selesai</p>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-amber-900/10 shadow-xs">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-900/50">Total HPP / COGS (Bahan)</span>
            <p class="text-xl font-black text-amber-800 mt-2">Rp {{ number_format($cogs ?? 0, 0, ',', '.') }}</p>
            <p class="text-[10px] text-amber-900/60 font-semibold mt-1">Modal Bahan Baku Terpakai</p>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-amber-900/10 shadow-xs">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-900/50">Pengeluaran Operasional</span>
            <p class="text-xl font-black text-rose-700 mt-2">Rp {{ number_format($operatingExpenses ?? 0, 0, ',', '.') }}</p>
            <p class="text-[10px] text-amber-900/60 font-semibold mt-1">Beban Non-Bahan Baku</p>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-amber-900/10 shadow-xs">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-900/50">Laba Bersih (Net Profit)</span>
            <p class="text-xl font-black {{ ($netProfit ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700' }} mt-2">Rp {{ number_format($netProfit ?? 0, 0, ',', '.') }}</p>
            <p class="text-[10px] text-amber-900/60 font-semibold mt-1">Hasil Akhir Keuntungan</p>
        </div>
    </div>

    <!-- Tabel Detail Laba Rugi -->
    <div class="bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs">
        <h2 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900 mb-4">Laporan Laba Rugi (Profit & Loss Statement)</h2>
        <div class="space-y-3 text-xs">
            <div class="flex justify-between py-2 border-b border-amber-900/10 font-bold text-espresso-900">
                <span>PENDAPATAN PENJUALAN KOTOR (GROSS SALES)</span>
                <span>Rp {{ number_format($grossSales ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-amber-900/5 text-amber-900/70 font-semibold pl-4">
                <span>(-) Harga Pokok Penjualan / COGS (Bahan Baku)</span>
                <span class="text-rose-600">(Rp {{ number_format($cogs ?? 0, 0, ',', '.') }})</span>
            </div>
            <div class="flex justify-between py-2 border-b border-amber-900/10 font-black text-amber-800 bg-cream-50/50 px-3 rounded-xl">
                <span>LABA KOTOR (GROSS PROFIT)</span>
                <span>Rp {{ number_format($grossProfit ?? 0, 0, ',', '.') }}</span>
            </div>

            <div class="pt-2 font-bold text-espresso-900">BEBAN PENGELUARAN OPERASIONAL (OPERATING EXPENSES)</div>
            @forelse($expenseCategories ?? [] as $exp)
                <div class="flex justify-between py-1.5 border-b border-amber-900/5 text-amber-900/70 font-semibold pl-4">
                    <span>• {{ $exp->name }}</span>
                    <span class="text-rose-600">(Rp {{ number_format($exp->total_amount ?? 0, 0, ',', '.') }})</span>
                </div>
            @empty
                <div class="text-[11px] text-amber-900/40 italic pl-4 py-1">Tidak ada pengeluaran operasional pada periode ini.</div>
            @endforelse

            <div class="flex justify-between py-2 border-b border-amber-900/10 font-bold text-rose-700 pl-4">
                <span>TOTAL BEBAN OPERASIONAL</span>
                <span>(Rp {{ number_format($operatingExpenses ?? 0, 0, ',', '.') }})</span>
            </div>

            <div class="flex justify-between py-3 font-black text-sm {{ ($netProfit ?? 0) >= 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-4 rounded-2xl border border-amber-900/10 mt-4">
                <span>LABA BERSIH (NET PROFIT)</span>
                <span>Rp {{ number_format($netProfit ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection