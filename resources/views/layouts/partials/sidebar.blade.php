<aside x-data="{ open: false }" class="w-64 shrink-0 border-r border-slate-200/60 dark:border-slate-800/60
               bg-white dark:bg-slate-900 hidden lg:block">
  <nav class="p-3 space-y-1">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl
       hover:bg-slate-100 dark:hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-slate-100 dark:bg-slate-800' : '' }}">
      <span>🏠</span><span class="text-sm font-medium">Dashboard</span>
    </a>

    <a href="{{ route('movimenti.transfer') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl
       hover:bg-slate-100 dark:hover:bg-slate-800 {{ request()->routeIs('movimenti.transfer') ? 'bg-slate-100 dark:bg-slate-800' : '' }}">
      <span>🔁</span><span class="text-sm font-medium">Trasferimenti</span>
    </a>

    {{-- Placeholder per altre sezioni --}}
    <a href="/articoli" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
      <span>📦</span><span class="text-sm font-medium">Articoli</span>
    </a>
    <a href="/modelli" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
      <span>🏷️</span><span class="text-sm font-medium">Modelli</span>
    </a>
    <a href="/report" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
      <span>📊</span><span class="text-sm font-medium">Report</span>
    </a>
    <a href="/settings" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
      <span>⚙️</span><span class="text-sm font-medium">Impostazioni</span>
    </a>
  </nav>
</aside>
