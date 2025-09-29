<!-- resources/views/layout.blade.php -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'MAS Gestionale') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.css') }}">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ config('app.name', 'MAS Gestionale') }}</h1>
            <nav class="space-x-4">
                <a href="/dashboard" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                <a href="/movimentazioni" class="text-gray-600 hover:text-gray-900">Movimentazioni</a>
                <a href="/ordini" class="text-gray-600 hover:text-gray-900">Ordini</a>
                <a href="/articoli" class="text-gray-600 hover:text-gray-900">Articoli</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto p-6">
        {{ $slot }}
    </main>
    <!-- Footer -->
    <footer class="bg-white border-t p-4">
        <div class="container mx-auto text-center text-gray-600">
            &copy; {{ date('Y') }} MAS Gestionale
        </div>
    </footer>

    @livewireScripts
</body>
</html>
