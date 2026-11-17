@extends('layouts.app')

@section('title', 'Bahan Baku & Stok')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-black text-espresso-900 tracking-tight">Bahan Baku (Ingredients)</h1>
        <p class="text-xs text-amber-900/60 font-semibold mt-1">Kelola stok awal dan modal per satuan bahan baku.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Bahan Baku Baru -->
        <div class="bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs h-fit">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900 mb-4">Tambah Bahan Baku Baru</h2>
            
            <form action="{{ route('owner.ingredients.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="code" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Kode Bahan</label>
                    <input type="text" name="code" id="code" required placeholder="ING-001"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>

                <div>
                    <label for="name" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Nama Bahan</label>
                    <input type="text" name="name" id="name" required placeholder="Biji Kopi Espresso Blend"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>

                <div>
                    <label for="unit" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Satuan</label>
                    <select name="unit" id="unit" required class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                        <option value="Gram (g)">Gram (g)</option>
                        <option value="Milliliter (ml)">Milliliter (ml)</option>
                        <option value="Pcs / Buah">Pcs / Buah</option>
                        <option value="Kilogram (kg)">Kilogram (kg)</option>
                        <option value="Liter (l)">Liter (l)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="initial_stock" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Stok Awal</label>
                        <input type="number" step="0.01" name="initial_stock" id="initial_stock" required placeholder="1000"
                               class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                    </div>
                    <div>
                        <label for="minimum_stock" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Stok Min Alert</label>
                        <input type="number" step="0.01" name="minimum_stock" id="minimum_stock" required placeholder="100"
                               class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                    </div>
                </div>

                <div>
                    <label for="cost_per_unit" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Harga Modal Per Satuan (Rp)</label>
                    <input type="number" step="0.01" name="cost_per_unit" id="cost_per_unit" required placeholder="250 (Rp 250 / gram)"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-extrabold rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all active:scale-98">
                    + Simpan Bahan Baku
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Bahan Baku -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900">Daftar Bahan Baku</h2>
                <input type="text" id="searchIngredient" onkeyup="filterIngredients()" placeholder="Cari bahan..." 
                       class="px-3 py-1.5 rounded-xl border border-amber-900/10 text-xs font-semibold focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="ingredientTable">
                    <thead class="bg-cream-100/50 text-espresso-900 font-bold uppercase border-b border-amber-900/10">
                        <tr>
                            <th class="p-3">Kode</th>
                            <th class="p-3">Nama Bahan</th>
                            <th class="p-3">Stok Saat Ini</th>
                            <th class="p-3">Harga / Satuan</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-900/5">
                        @forelse($ingredients as $ing)
                            <tr class="hover:bg-cream-50/50">
                                <td class="p-3 font-mono text-amber-900/60">{{ $ing->code }}</td>
                                <td class="p-3 font-bold text-espresso-900 ing-name">{{ $ing->name }}</td>
                                <td class="p-3 font-extrabold {{ $ing->current_stock <= $ing->minimum_stock ? 'text-rose-600' : 'text-emerald-700' }}">
                                    <span class="px-2.5 py-1 rounded-xl {{ $ing->current_stock <= $ing->minimum_stock ? 'bg-rose-50 border border-rose-200' : 'bg-emerald-50 border border-emerald-200' }}">
                                        {{ number_format($ing->current_stock, 1) }} {{ $ing->unit }}
                                    </span>
                                </td>
                                <td class="p-3 font-semibold text-espresso-900">Rp {{ number_format($ing->cost_per_unit, 2, ',', '.') }} / {{ $ing->unit }}</td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('owner.ingredients.destroy', $ing) }}" method="POST" onsubmit="return confirm('Hapus bahan baku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-amber-900/40 font-semibold">Belum ada bahan baku yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function filterIngredients() {
        const q = document.getElementById('searchIngredient').value.toLowerCase();
        document.querySelectorAll('#ingredientTable tbody tr').forEach(tr => {
            const nameEl = tr.querySelector('.ing-name');
            if (nameEl) {
                tr.style.display = nameEl.innerText.toLowerCase().includes(q) ? '' : 'none';
            }
        });
    }
</script>
@endsection