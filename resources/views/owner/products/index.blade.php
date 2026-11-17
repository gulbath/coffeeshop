@extends('layouts.app')

@section('title', 'Manajemen Produk & Menu')

@section('content')
<div class="space-y-6">
    <!-- Header Summary & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-espresso-900 tracking-tight">Produk & Menu</h1>
            <p class="text-xs text-amber-900/60 font-semibold mt-1">Daftar seluruh menu coffee shop yang dijual.</p>
        </div>
        <div>
            <a href="{{ route('owner.products.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-extrabold rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Produk Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-3xl border border-amber-900/10 shadow-xs">
        <form method="GET" action="{{ route('owner.products.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Nama / SKU..." 
                       class="w-full px-3.5 py-2 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>
            <div>
                <select name="category_id" class="w-full sm:w-auto px-3.5 py-2 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full sm:w-auto px-3.5 py-2 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                    <option value="">-- Semua Status --</option>
                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-latte-500 hover:bg-amber-600 text-white font-extrabold rounded-2xl text-xs transition-colors shadow-xs">Filter</button>
                <a href="{{ route('owner.products.index') }}" class="px-4 py-2 bg-cream-200 hover:bg-cream-300 text-espresso-900 font-extrabold rounded-2xl text-xs transition-colors">Reset</a>
            </div>
        </form>
    </div>

    <!-- Tabel Produk -->
    <div class="bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-100/50 text-espresso-900 font-bold uppercase border-b border-amber-900/10">
                    <tr>
                        <th class="p-3">Gambar</th>
                        <th class="p-3">SKU / Code</th>
                        <th class="p-3">Nama Menu</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Harga Jual</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-900/5">
                    @forelse($products as $product)
                        <tr class="hover:bg-cream-50/50">
                            <td class="p-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-cream-100 border border-amber-900/10 flex items-center justify-center">
                                    @if($product->image_path && Storage::disk('public')->exists($product->image_path))
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-amber-900/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>
                                    @endif
                                </div>
                            </td>
                            <td class="p-3 font-mono text-amber-900/60">{{ $product->code }}</td>
                            <td class="p-3 font-bold text-espresso-900">{{ $product->name }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-xl bg-amber-50 text-amber-800 font-extrabold text-[10px] border border-amber-900/10">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="p-3 font-black text-emerald-700">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase {{ $product->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $product->status }}
                                </span>
                            </td>
                            <td class="p-3 text-right space-x-2">
                                <a href="{{ route('owner.products.recipes.show', $product) }}" class="text-blue-600 hover:text-blue-800 font-bold">Resep</a>
                                <a href="{{ route('owner.products.edit', $product) }}" class="text-amber-600 hover:text-amber-800 font-bold">Edit</a>
                                <form action="{{ route('owner.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-amber-900/40 font-semibold">Tidak ada data produk ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection