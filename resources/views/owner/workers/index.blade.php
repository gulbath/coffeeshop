@extends('layouts.app')

@section('title', 'Kelola Staf & Kasir')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-black text-espresso-900 tracking-tight">Kelola Worker & Kasir</h1>
        <p class="text-xs text-amber-900/60 font-semibold mt-1">Manajemen akun staf operasional coffee shop Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Worker Baru -->
        <div class="bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs h-fit">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900 mb-4">Tambah Worker Baru</h2>
            
            <form action="{{ route('owner.workers.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required placeholder="Budi Santoso"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>

                <div>
                    <label for="email" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Email (Username Login)</label>
                    <input type="email" name="email" id="email" required placeholder="worker@coffeeshop.com"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>

                <div>
                    <label for="phone" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Nomor WhatsApp / Telepon</label>
                    <input type="text" name="phone" id="phone" placeholder="08123456789"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Kata Sandi Awal</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>

                <div>
                    <label for="is_active" class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-900/60 mb-1">Status Akun</label>
                    <select name="is_active" id="is_active" required class="w-full px-3.5 py-2.5 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                        <option value="1">Aktif (Bisa Login)</option>
                        <option value="0">Nonaktif (Blokir Login)</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-extrabold rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all active:scale-98">
                    + Buat Akun Worker
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Worker -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-amber-900/10 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-extrabold uppercase tracking-wider text-espresso-900">Daftar Worker / Kasir</h2>
                <input type="text" id="searchWorker" onkeyup="filterWorkers()" placeholder="Cari nama / email..." 
                       class="px-3 py-1.5 rounded-xl border border-amber-900/10 text-xs font-semibold focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="workerTable">
                    <thead class="bg-cream-100/50 text-espresso-900 font-bold uppercase border-b border-amber-900/10">
                        <tr>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Email & Kontak</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-900/5">
                        @forelse($workers as $w)
                            <tr class="hover:bg-cream-50/50">
                                <td class="p-3 font-bold text-espresso-900 worker-name">{{ $w->name }}</td>
                                <td class="p-3">
                                    <p class="font-medium text-espresso-900 worker-email">{{ $w->email }}</p>
                                    <p class="text-[10px] text-amber-900/60">{{ $w->phone ?? '-' }}</p>
                                </td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase {{ $w->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ $w->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="p-3 text-right space-x-2">
                                    <form action="{{ route('owner.workers.toggleStatus', $w) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-amber-600 hover:text-amber-800 font-bold">
                                            {{ $w->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.workers.destroy', $w) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun worker ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-amber-900/40 font-semibold">Belum ada staf worker yang didaftarkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function filterWorkers() {
        const q = document.getElementById('searchWorker').value.toLowerCase();
        document.querySelectorAll('#workerTable tbody tr').forEach(tr => {
            const nameEl = tr.querySelector('.worker-name');
            const emailEl = tr.querySelector('.worker-email');
            if (nameEl || emailEl) {
                const match = (nameEl && nameEl.innerText.toLowerCase().includes(q)) || 
                              (emailEl && emailEl.innerText.toLowerCase().includes(q));
                tr.style.display = match ? '' : 'none';
            }
        });
    }
</script>
@endsection