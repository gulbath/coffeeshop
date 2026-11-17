<header class="bg-white border-b border-cream-300 px-6 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
    <div class="flex items-center gap-4">
        <h2 class="text-lg font-bold text-espresso-900">
            @yield('title', 'Dashboard')
        </h2>
    </div>

    <div class="flex items-center gap-4">
        <!-- Date Display -->
        <div class="text-xs text-gray-500 font-medium hidden sm:block">
            📅 {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
        </div>

        <!-- Role Badge -->
        <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider shadow-sm
            {{ auth()->user()->isOwner() ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300' }}">
            {{ auth()->user()->role }}
        </span>
    </div>
</header>