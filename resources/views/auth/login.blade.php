<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - CoffeeShop Management System</title>
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
            <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-latte-500/10 blur-2xl"></div>
            
            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-gradient-to-br from-latte-500 to-amber-700 text-espresso-900 flex items-center justify-center shadow-lg shadow-amber-900/40">
                <svg class="w-7 h-7 text-espresso-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                </svg>
            </div>
            
            <h1 class="text-xl font-black text-cream-50 tracking-tight">CoffeeShop System</h1>
            <p class="text-xs text-latte-500 font-bold uppercase tracking-widest mt-1">Portal Akses Staf & Owner</p>
        </div>

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST" class="p-8 space-y-4">
            @csrf

            @if($errors->any())
                <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label for="email" class="block text-xs font-extrabold text-amber-900/60 uppercase tracking-wider mb-1.5">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-900/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           placeholder="nama@coffeeshop.com"
                           class="w-full pl-10 pr-4 py-3 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-extrabold text-amber-900/60 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-900/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input type="password" name="password" id="password" required
                           placeholder="••••••••"
                           class="w-full pl-10 pr-4 py-3 rounded-2xl border border-amber-900/10 text-xs font-bold text-espresso-900 focus:ring-2 focus:ring-latte-500 focus:outline-none bg-cream-50/50">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-amber-900/20 text-latte-500 focus:ring-latte-500">
                    <span class="font-bold text-amber-900/60">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-latte-500 to-amber-600 hover:from-latte-600 hover:to-amber-700 text-white font-black rounded-2xl text-xs shadow-md shadow-amber-900/20 transition-all active:scale-98 mt-2">
                Masuk ke Sistem
            </button>
        </form>

        <div class="p-4 bg-cream-100/50 border-t border-amber-900/5 text-center text-[10px] text-amber-900/40 font-bold">
            &copy; 2026 CoffeeShop Management System
        </div>
    </div>

</body>
</html>