@extends('layouts.app')

@section('title', 'Resep Menu - ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-espresso-900">Kelola Resep: {{ $product->name }}</h1>
            <p class="text-xs text-gray-500 mt-1">Kategori: {{ $product->category->name }} | Harga Jual: <span class="font-bold text-emerald-700">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span></p>
        </div>
        <a href="{{ route('owner.products.index') }}" class="text-xs font-bold text-gray-500 hover:text-espresso-900">← Kembali ke Produk</a>
    </div>

    <!-- Ringkasan HPP (COGS) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-sm">
            <p class="text-[10px] font-bold uppercase text-gray-400">Harga Jual Menu</p>
            <p class="text-lg font-bold text-espresso-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-sm">
            <p class="text-[10px] font-bold uppercase text-gray-400">Estimasi COGS / HPP Resep</p>
            <p class="text-lg font-bold text-amber-700">Rp {{ number_format($cogs, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-sm">
            <p class="text-[10px] font-bold uppercase text-gray-400">Estimasi Margin Keuntungan</p>
            <p class="text-lg font-bold text-emerald-700">
                Rp {{ number_format($product->selling_price - $cogs, 0, ',', '.') }} 
                <span class="text-xs font-normal">({{ $product->selling_price > 0 ? number_format((($product->selling_price - $cogs) / $product->selling_price) * 100, 1) : 0 }}%)</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Bahan ke Resep (1 col) -->
        <div class="bg-white p-6 rounded-2xl border border-cream-300 shadow-sm h-fit">
            <h3 class="text-base font-bold text-espresso-900 mb-4">Tambah Takaran Bahan</h3>
            <form action="{{ route('owner.products.recipes.store', $product) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="ingredient_id" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Bahan Baku</label>
                    <select name="ingredient_id" id="ingredient_id" required class="w-full px-3 py-2 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                        <option value="">-- Pilih Bahan Baku --</option>
                        @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="quantity_required" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Takaran / Jumlah</label>
                    <input type="number" step="0.001" name="quantity_required" id="quantity_required" required placeholder="Contoh: 18 (untuk 18 gram)"
                           class="w-full px-3 py-2 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                </div>

                <button type="submit" class="w-full py-2.5 bg-espresso-800 hover:bg-espresso-700 text-gold-500 font-bold rounded-xl text-xs shadow transition-all">
                    + Masukkan ke Resep
                </button>
            </form>
        </div>

        <!-- Tabel Komposisi Resep (2 cols) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-cream-300 shadow-sm">
            <h3 class="text-base font-bold text-espresso-900 mb-4">Komposisi Resep Saat Ini</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cream-100 text-espresso-900 font-bold uppercase border-b border-cream-300">
                        <tr>
                            <th class="p-3">Bahan Baku</th>
                            <th class="p-3">Takaran Required</th>
                            <th class="p-3">Cost per Satuan</th>
                            <th class="p-3">Subtotal Cost</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-200">
                        @forelse($product->recipes as $recipe)
                            <tr class="hover:bg-cream-50">
                                <td class="p-3 font-bold text-espresso-900">{{ $recipe->ingredient->name }}</td>
                                <td class="p-3 font-semibold">{{ number_format($recipe->quantity_required, 1) }} {{ $recipe->ingredient->unit }}</td>
                                <td class="p-3 text-gray-500">Rp {{ number_format($recipe->ingredient->unit_cost, 2, ',', '.') }}</td>
                                <td class="p-3 font-bold text-amber-800">Rp {{ number_format($recipe->subtotal_cost, 0, ',', '.') }}</td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('owner.products.recipes.destroy', [$product, $recipe]) }}" method="POST" onsubmit="return confirm('Hapus bahan dari resep?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">Belum ada bahan baku yang didaftarkan pada resep menu ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection