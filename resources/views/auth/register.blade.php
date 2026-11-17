<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Staf - CoffeeShop Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        espresso: { 900: '#110C08', 800: '#1A120B', 700: '#2B1E16' },
                        cream: { 50: '#FDFBF7', 100: '#F8F3EC', 200: '#F0E7DB' },
                        latte: { 500: '#C58B4D', 600: '#B0783C' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-cream-50 font-sans text-espresso-900 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl border border-amber-900/10 shadow-xl overflow-hidden">
        
        <!-- Header Banner -->
        <div class="p-8 bg-espresso-800 text-center relative overflow-hidden">
            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-gradient-to-br from-latte-500 to-amber-700 text-espresso-900 flex items-center justify-center shadow-lg shadow-amber-900/40">
                <svg class="w-7 h-7 text-espresso-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                </svg>
            </div>
            
            <h1 class="text-xl font-black text-cream-50 tracking-tight">Buat Akun Staf</h1>
            <p class="text-xs text-latte-500 font-bold uppercase tracking-widest mt-1">Registrasi Pengguna Baru</p>
        </div>

        <!-- Form Register -->
        <form action="{{ route('register') }}" method="POST" class="p-8 space-y-4">
            @csrf

            @if($errors->any())
                <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label for="name" class="block text-xs font-extrabold text-amber-900/60 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       placeholder="Contoh: Budi Santoso"
                       class="w-full px-4 py-3 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>

            <div>
                <label for="email" class="block text-xs font-extrabold text-amber-900/60 uppercase tracking-wider mb-1.5">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       placeholder="nama@coffeeshop.com"
                       class="w-full px-4 py-3 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>

            <div>
                <label for="password" class="block text-xs font-extrabold text-amber-900/60 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <input type="password" name="password" id="password" required placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-extrabold text-amber-900/60 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-black rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all active:scale-98 mt-2">
                Daftar Akun
            </button>
        </form>

        <div class="p-4 bg-cream-100/50 border-t border-amber-900/5 text-center text-xs text-amber-900/60 font-semibold">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-latte-600 font-bold hover:underline">Masuk di sini</a>
        </div>
    </div>

</body>
</html>