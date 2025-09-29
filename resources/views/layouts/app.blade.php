<!doctype html>
<html lang="it" class="h-full" x-data="layoutState()" x-init="init()" :class="{ dark: isDark, 'overflow-hidden': mobileNavOpen }">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name'))</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script>
    function layoutState(){
      return {
        isDark: false,
        mobileNavOpen: false,
        init(){
          this.isDark = localStorage.theme === 'dark'
            || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
          window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
              this.mobileNavOpen = false;
            }
          });
        },
        toggleTheme(){
          this.isDark = !this.isDark;
          localStorage.theme = this.isDark ? 'dark' : 'light';
        },
        openMobileNav(){ this.mobileNavOpen = true; },
        closeMobileNav(){ this.mobileNavOpen = false; }
      }
    }
  </script>
</head>
<body class="min-h-full">
  <div class="min-h-screen">

    {{-- Topbar --}}
    @include('layouts.partials.topbar')

    {{-- Mobile navigation drawer --}}
    <div
      x-cloak
      x-show="mobileNavOpen"
      x-transition.opacity
      class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm lg:hidden"
      role="dialog"
      aria-modal="true"
      @keydown.escape.window="closeMobileNav()"
    >
      <div class="absolute inset-y-0 right-0 w-80 max-w-full bg-white dark:bg-slate-900 shadow-xl flex flex-col"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="translate-x-full">
        <div class="flex items-center justify-between p-4 border-b border-slate-200/60 dark:border-slate-800/60">
          <div class="flex items-center gap-2">
            <div class="h-9 w-9 rounded-xl bg-brand-600 text-white grid place-items-center font-bold">M</div>
            <div>
              <div class="text-sm font-semibold">MAS — Portal</div>
              <div class="text-xs text-slate-500 dark:text-slate-400">Navigazione rapida</div>
            </div>
          </div>
          <button type="button" class="btn-ghost" @click="closeMobileNav()">Chiudi</button>
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
          @include('layouts.partials.menu-items', ['variant' => 'mobile'])
        </div>
        <div class="p-4 border-t border-slate-200/60 dark:border-slate-800/60">
          @auth
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-700 grid place-items-center text-sm font-semibold">
                {{ strtoupper(substr(auth()->user()->name,0,2)) }}
              </div>
              <div class="flex-1">
                <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</div>
              </div>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn-secondary">Esci</button>
              </form>
            </div>
          @else
            <div class="flex flex-col gap-2">
              <a href="{{ route('login') }}" class="btn-primary">Accedi</a>
              @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-secondary">Registrati</a>
              @endif
            </div>
          @endauth
        </div>
      </div>
      <button type="button" class="absolute inset-0 w-full" @click.self="closeMobileNav()" aria-label="Chiudi navigazione"></button>
    </div>

    <div class="flex">
      {{-- Sidebar (desktop) --}}
      @include('layouts.partials.sidebar')

      {{-- Main content --}}
      <main class="flex-1">
        <div class="container-page">
          @yield('content')
          {{ $slot ?? '' }}
        </div>
      </main>
    </div>
  </div>

  {{-- Toast sessioni --}}
  <x-ui.toast />

  @livewireScripts
</body>
</html>
