@extends('layouts.app')

@section('title', 'Panel Arus Kas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-espresso-900">Panel Arus Kas (Pemasukan & Pengeluaran)</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola seluruh mutasi kas keluar dan masuk dalam satu tampilan terpadu.</p>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-sm">
        <form action="{{ route('owner.financials.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-3">
            <div>
                <label for="start_date" class="block text-[10px] font-bold text-espresso-900 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" 
                       class="px-3 py-2 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
            </div>
            <div>
                <label for="end_date" class="block text-[10px] font-bold text-espresso-900 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" 
                       class="px-3 py-2 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
            </div>
            <button type="submit" class="px-5 py-2 bg-gold-500 hover:bg-gold-600 text-espresso-900 font-bold rounded-xl text-xs shadow">
                Filter Arus Kas
            </button>
        </form>
    </div>

    <!-- Ringkasan Arus Kas -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-sm">
            <span class="text-[10px] font-bold uppercase text-gray-400">Total Pemasukan (Omset)</span>
            <p class="text-xl font-extrabold text-emerald-700 mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-sm">
            <span class="text-[10px] font-bold uppercase text-gray-400">Total Pengeluaran</span>
            <p class="text-xl font-extrabold text-rose-700 mt-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-sm">
            <span class="text-[10px] font-bold uppercase text-gray-400">Kas Bersih (Net Cash Flow)</span>
            <p class="text-xl font-extrabold {{ $netCashFlow >= 0 ? 'text-blue-700' : 'text-rose-700' }} mt-1">
                Rp {{ number_format($netCashFlow, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Panel Terpadu Pemasukan & Pengeluaran -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Tabel Pemasukan -->
        <div class="bg-white p-6 rounded-2xl border border-cream-300 shadow-sm">
            <h3 class="text-base font-bold text-emerald-800 mb-4 flex items-center gap-2">
                <span>🟢</span> Pemasukan (Transaksi POS)
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cream-100 text-espresso-900 font-bold uppercase border-b border-cream-300">
                        <tr>
                            <th class="p-2.5">No. Trx</th>
                            <th class="p-2.5">Waktu</th>
                            <th class="p-2.5">Kasir</th>
                            <th class="p-2.5 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-200">
                        @forelse($incomes as $inc)
                            <tr class="hover:bg-cream-50">
                                <td class="p-2.5 font-mono font-bold text-espresso-900">{{ $inc->transaction_number }}</td>
                                <td class="p-2.5 text-gray-500">{{ $inc->created_at->format('d/m H:i') }}</td>
                                <td class="p-2.5">{{ $inc->user->name ?? 'Kasir' }}</td>
                                <td class="p-2.5 text-right font-bold text-emerald-700">Rp {{ number_format($inc->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">Belum ada transaksi pemasukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $incomes->links() }}
            </div>
        </div>

        <!-- Tabel Pengeluaran Operasional -->
        <div class="bg-white p-6 rounded-2xl border border-cream-300 shadow-sm">
            <h3 class="text-base font-bold text-rose-800 mb-4 flex items-center gap-2">
                <span>🔴</span> Pengeluaran Operasional
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cream-100 text-espresso-900 font-bold uppercase border-b border-cream-300">
                        <tr>
                            <th class="p-2.5">Tanggal</th>
                            <th class="p-2.5">Kategori</th>
                            <th class="p-2.5">Keterangan</th>
                            <th class="p-2.5 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-200">
                        @forelse($expenses as $exp)
                            <tr class="hover:bg-cream-50">
                                <td class="p-2.5 font-medium text-gray-500">{{ $exp->expense_date->format('d/m/Y') }}</td>
                                <td class="p-2.5 font-bold text-espresso-900">{{ $exp->category }}</td>
                                <td class="p-2.5 text-gray-600 max-w-xs truncate">{{ $exp->notes }}</td>
                                <td class="p-2.5 text-right font-bold text-rose-700">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">Belum ada pencatatan pengeluaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $expenses->links() }}
            </div>
        </div>

    </div>
</div>
@endsection