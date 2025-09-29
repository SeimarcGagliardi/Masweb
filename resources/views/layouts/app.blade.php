<!doctype html>
<html lang="it" class="h-full" x-data="themeState()" x-init="init()" :class="{ dark: isDark }">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name'))</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script>
    function themeState(){
      return {
        isDark: false,
        init(){ this.isDark = localStorage.theme === 'dark' || ( !('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches ) },
        toggle(){ this.isDark = !this.isDark; localStorage.theme = this.isDark ? 'dark' : 'light' }
      }
    }
  </script>
</head>
<body class="min-h-full">
  <div class="min-h-screen">

    {{-- Topbar --}}
    @include('layouts.partials.topbar')

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
