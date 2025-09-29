<header class="sticky top-0 z-40 backdrop-blur supports-[backdrop-filter]:bg-white/60 bg-white/90
               dark:bg-slate-950/80 dark:supports-[backdrop-filter]:bg-slate-950/60 border-b border-slate-200/60 dark:border-slate-800/60">
  <div class="container-page py-3 flex items-center gap-3">
    {{-- Mobile menu toggle --}}
    <button type="button" class="btn-ghost lg:hidden" @click="openMobileNav()" aria-label="Apri menu">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
        <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </button>

    {{-- Logo + brand --}}
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
      <div class="h-8 w-8 rounded-xl bg-brand-600 text-white grid place-items-center font-bold">M</div>
      <div class="text-sm font-semibold">MAS — Portal</div>
    </a>

    {{-- Spacer --}}
    <div class="flex-1"></div>

    {{-- Dark toggle --}}
    <button @click="toggleTheme()" class="btn-ghost" aria-label="Cambia tema">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="currentColor"><path d="M21.64 13.02A9 9 0 1 1 10.98 2.36 7 7 0 0 0 21.64 13.02Z"/></svg>
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
    </button>

    {{-- Utente --}}
    <div class="hidden sm:flex sm:items-center sm:ms-4">
      @auth
        <div class="flex items-center gap-2">
          <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700 grid place-items-center text-xs font-semibold">
            {{ strtoupper(substr(auth()->user()->name,0,2)) }}
          </div>
          <div class="text-sm">{{ auth()->user()->name }}</div>
          <form action="{{ route('logout') }}" method="POST" class="ms-2">
            @csrf
            <button class="btn-secondary">Esci</button>
          </form>
        </div>
      @endauth
      @guest
        <a href="{{ route('login') }}" class="btn-secondary">Accedi</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="btn-ghost">Registrati</a>
        @endif
      @endguest
    </div>
  </div>
</header>
