@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-black text-espresso-900 tracking-tight">Kategori Produk</h1>
        <p class="text-xs text-amber-900/60 font-semibold mt-1">Kelola kategori menu coffee shop Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Kategori Baru -->
        <div class="bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs h-fit">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900 mb-4">Tambah Kategori Baru</h2>
            
            <form action="{{ route('owner.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Nama Kategori</label>
                    <input type="text" name="name" id="name" required placeholder="Contoh: Espresso Based"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>

                <div>
                    <label for="description" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="3" placeholder="Keterangan kategori..."
                              class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-extrabold rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all active:scale-98">
                    + Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Kategori -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900">Daftar Kategori</h2>
                <input type="text" id="searchCategory" onkeyup="filterCategories()" placeholder="Cari kategori..." 
                       class="px-3 py-1.5 rounded-xl border border-amber-900/10 text-xs font-semibold focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="categoryTable">
                    <thead class="bg-cream-100/50 text-espresso-900 font-bold uppercase border-b border-amber-900/10">
                        <tr>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Slug</th>
                            <th class="p-3">Jumlah Produk</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-900/5">
                        @forelse($categories as $category)
                            <tr class="hover:bg-cream-50/50">
                                <td class="p-3 font-bold text-espresso-900 cat-name">{{ $category->name }}</td>
                                <td class="p-3 font-mono text-amber-900/60">{{ $category->slug }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-xl bg-amber-50 text-amber-800 font-extrabold text-[10px] border border-amber-900/10">
                                        {{ $category->products_count }} Produk
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('owner.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-amber-900/40 font-semibold">Belum ada kategori yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function filterCategories() {
        const q = document.getElementById('searchCategory').value.toLowerCase();
        document.querySelectorAll('#categoryTable tbody tr').forEach(tr => {
            const nameEl = tr.querySelector('.cat-name');
            if (nameEl) {
                tr.style.display = nameEl.innerText.toLowerCase().includes(q) ? '' : 'none';
            }
        });
    }
</script>
@endsection