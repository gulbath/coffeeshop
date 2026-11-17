@extends('layouts.app')

@section('title', 'Pengeluaran Operasional')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-espresso-900">Pengeluaran Operasional</h1>
            <p class="text-xs text-gray-500 mt-1">Catat beban biaya operasional di luar bahan baku (Listerik, Air, Gaji, dll).</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Pengeluaran (1 col) -->
        <div class="bg-white p-6 rounded-2xl border border-cream-300 shadow-sm h-fit">
            <h3 class="text-base font-bold text-espresso-900 mb-4">Catat Pengeluaran Baru</h3>
            <form action="{{ route('owner.expenses.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label for="category" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Kategori Pengeluaran</label>
                    <select name="category" id="category" required class="w-full px-3 py-2 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                        <option value="Sewa Tempat">Sewa Tempat / Outlet</option>
                        <option value="Listrik & Air">Listrik & Air</option>
                        <option value="Gaji Karyawan">Gaji Karyawan</option>
                        <option value="Marketing & Promosi">Marketing & Promosi</option>
                        <option value="Perawatan Alat">Perawatan Alat & Mesin</option>
                        <option value="Perlengkapan Outlet">Perlengkapan Outlet</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>

                <div>
                    <label for="amount" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" id="amount" min="1" required placeholder="Contoh: 350000"
                           class="w-full px-3 py-2 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                </div>

                <div>
                    <label for="expense_date" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Tanggal Pengeluaran</label>
                    <input type="date" name="expense_date" id="expense_date" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Keterangan / Rincian</label>
                    <textarea name="notes" id="notes" rows="3" required placeholder="Pembayaran listrik PLN bulan ini..."
                              class="w-full px-3 py-2 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-espresso-800 hover:bg-espresso-700 text-gold-500 font-bold rounded-xl text-xs shadow transition-all mt-2">
                    + Simpan Pengeluaran
                </button>
            </form>
        </div>

        <!-- Tabel Catatan Pengeluaran (2 cols) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-cream-300 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-espresso-900">Riwayat Pengeluaran</h3>
                <form action="{{ route('owner.expenses.index') }}" method="GET" class="w-48 sm:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari keterangan..."
                           class="w-full px-3 py-1.5 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cream-100 text-espresso-900 font-bold uppercase border-b border-cream-300">
                        <tr>
                            <th class="p-3">No. Ref</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Nominal</th>
                            <th class="p-3">Keterangan</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-200">
                        @forelse($expenses as $exp)
                            <tr class="hover:bg-cream-50">
                                <td class="p-3 font-mono text-gray-500">{{ $exp->expense_number }}</td>
                                <td class="p-3 font-medium">{{ $exp->expense_date->format('d/m/Y') }}</td>
                                <td class="p-3 font-bold text-espresso-900"><span class="px-2 py-0.5 bg-cream-200 rounded">{{ $exp->category }}</span></td>
                                <td class="p-3 font-bold text-rose-700">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                                <td class="p-3 text-gray-600 max-w-xs truncate">{{ $exp->notes }}</td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('owner.expenses.destroy', $exp) }}" method="POST" onsubmit="return confirm('Hapus pencatatan pengeluaran ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">Belum ada pencatatan pengeluaran.</td>
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