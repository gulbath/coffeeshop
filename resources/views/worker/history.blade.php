@extends('layouts.app')

@section('title', 'Riwayat Transaksi Kasir')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-espresso-900">Riwayat Transaksi Kasir</h1>
            <p class="text-xs text-gray-500 mt-1">Daftar transaksi pesanan pelanggan yang telah diproses.</p>
        </div>
        <div>
            <a href="{{ route('worker.pos') }}" class="px-4 py-2 bg-gold-500 hover:bg-gold-600 text-espresso-900 font-bold rounded-xl text-xs shadow transition-all flex items-center gap-2">
                🖥️ Kembali ke POS Kasir
            </a>
        </div>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="bg-white p-6 rounded-2xl border border-cream-300 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-espresso-900">Daftar Transaksi Selesai</h3>
            <form action="{{ route('worker.history') }}" method="GET" class="w-48 sm:w-64">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. TRX..."
                       class="w-full px-3 py-1.5 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-100 text-espresso-900 font-bold uppercase border-b border-cream-300">
                    <tr>
                        <th class="p-3">No. Transaksi</th>
                        <th class="p-3">Waktu</th>
                        <th class="p-3">Item Pesanan</th>
                        <th class="p-3">Metode</th>
                        <th class="p-3">Diskon</th>
                        <th class="p-3 text-right">Total Tagihan</th>
                        <th class="p-3 text-right">Bayar / Kembali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-cream-50">
                            <td class="p-3 font-mono font-bold text-espresso-900">{{ $trx->transaction_number }}</td>
                            <td class="p-3 text-gray-500 font-medium">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3">
                                <ul class="space-y-1">
                                    @foreach($trx->items as $item)
                                        <li class="text-gray-700">
                                            <span class="font-bold text-espresso-900">• {{ $item->product_name }}</span> 
                                            <span class="text-gray-500">({{ $item->quantity }}x @ Rp {{ number_format($item->unit_price, 0, ',', '.') }})</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 bg-cream-200 rounded-lg font-bold uppercase text-[10px] text-espresso-900">
                                    {{ $trx->payment_method }}
                                </span>
                            </td>
                            <td class="p-3 text-rose-600 font-semibold">
                                {{ $trx->discount_amount > 0 ? 'Rp ' . number_format($trx->discount_amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="p-3 text-right font-extrabold text-emerald-700">
                                Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-right text-gray-600">
                                <p class="font-medium text-[11px]">Diterima: Rp {{ number_format($trx->cash_amount, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-blue-600">Kembali: Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">Belum ada riwayat transaksi recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection