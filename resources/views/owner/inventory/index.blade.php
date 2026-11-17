@extends('layouts.app')

@section('title', 'Stok & Mutasi Inventori')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-black text-espresso-900 tracking-tight">Stok & Mutasi Inventori</h1>
        <p class="text-xs text-amber-900/60 font-semibold mt-1">Audit mutasi bahan baku (Restock, Waste/Spill, dan Stock Opname).</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Mutasi Stok -->
        <div class="bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs h-fit">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900 mb-4">Catat Mutasi Stok</h2>
            
            <form action="{{ route('owner.inventory.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="ingredient_id" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Bahan Baku</label>
                    <select name="ingredient_id" id="ingredient_id" required class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                        <option value="">-- Pilih Bahan Baku --</option>
                        @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}">{{ $ing->name }} (Sisa: {{ number_format($ing->current_stock, 1) }} {{ $ing->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Jenis Mutasi</label>
                    <select name="type" id="type" required class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                        <option value="in">IN (Restock / Pembelian Masuk)</option>
                        <option value="out">OUT (Pengurangan / Rusak / Spilled)</option>
                        <option value="adjustment">ADJUSTMENT (Stock Opname Fisik)</option>
                    </select>
                </div>

                <div>
                    <label for="quantity" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Jumlah / Kuantitas</label>
                    <input type="number" step="0.01" name="quantity" id="quantity" required placeholder="Contoh: 500"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>

                <div>
                    <label for="notes" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Keterangan / Alasan</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Belanja mingguan toko A / Susu tumpah 200ml"
                              class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-extrabold rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all active:scale-98">
                    + Simpan Mutasi Stok
                </button>
            </form>
        </div>

        <!-- Tabel Riwayat Mutasi -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900">Riwayat Mutasi Stok</h2>
                <form method="GET" action="{{ route('owner.inventory.index') }}">
                    <select name="ingredient_id" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-amber-900/10 text-xs font-semibold focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                        <option value="">Semua Bahan</option>
                        @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}" {{ ($ingredientId ?? '') == $ing->id ? 'selected' : '' }}>{{ $ing->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cream-100/50 text-espresso-900 font-bold uppercase border-b border-amber-900/10">
                        <tr>
                            <th class="p-3">Waktu</th>
                            <th class="p-3">Bahan</th>
                            <th class="p-3">Tipe</th>
                            <th class="p-3">Stok Awal → Akhir</th>
                            <th class="p-3">Catatan</th>
                            <th class="p-3">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-900/5">
                        @forelse($mutations as $m)
                            <tr class="hover:bg-cream-50/50">
                                <td class="p-3 font-mono text-[11px] text-amber-900/60">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                                <td class="p-3 font-bold text-espresso-900">{{ $m->ingredient->name ?? '-' }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase {{ $m->type === 'in' ? 'bg-emerald-100 text-emerald-800' : ($m->type === 'out' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ strtoupper($m->type) }}
                                    </span>
                                </td>
                                <td class="p-3 font-semibold text-espresso-900">{{ number_format($m->stock_before, 1) }} → <span class="font-bold">{{ number_format($m->stock_after, 1) }} {{ $m->ingredient->unit ?? '' }}</span></td>
                                <td class="p-3 text-amber-900/70">{{ $m->notes ?? '-' }}</td>
                                <td class="p-3 font-medium text-espresso-900">{{ $m->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-amber-900/40 font-semibold">Belum ada catatan mutasi inventori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $mutations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection