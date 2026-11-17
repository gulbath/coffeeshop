@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-espresso-900">Edit Produk: {{ $product->name }}</h1>
        <a href="{{ route('owner.products.index') }}" class="text-xs font-bold text-gray-500 hover:text-espresso-900">← Kembali</a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-cream-300 shadow-sm">
        <form action="{{ route('owner.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="sku" class="block text-xs font-bold text-espresso-900 uppercase mb-1">SKU / Kode Produk</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                    @error('sku') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Kategori</label>
                    <select name="category_id" id="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                @error('name') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="selling_price" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Harga Jual (Rp)</label>
                    <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required min="0" step="500"
                           class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                    @error('selling_price') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Status Produk</label>
                    <select name="status" id="status" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active (Dapat dijual)</option>
                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-espresso-900 uppercase mb-1">Deskripsi Menu</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-xs focus:ring-2 focus:ring-gold-500 focus:outline-none">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-espresso-900 uppercase mb-1">Foto Produk Saat Ini</label>
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-16 h-16 object-cover rounded-xl border mb-2">
                @else
                    <p class="text-xs text-gray-400 mb-2">Belum ada foto.</p>
                @endif
                <input type="file" name="image" id="image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-cream-200 file:text-espresso-900">
                @error('image') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="w-full py-3 bg-espresso-800 hover:bg-espresso-700 text-gold-500 font-bold rounded-xl text-xs shadow">Perbarui Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection