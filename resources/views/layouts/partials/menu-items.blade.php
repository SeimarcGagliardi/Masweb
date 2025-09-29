@props(['variant' => 'desktop'])
@php
    $items = [
        [
            'route' => 'dashboard',
            'icon' => '🏠',
            'label' => 'Dashboard',
            'description' => 'Panoramica operativa e collegamenti rapidi.',
        ],
        [
            'route' => 'movimenti.transfer',
            'icon' => '🔁',
            'label' => 'Trasferimenti',
            'description' => 'Sposta merce tra sedi e ubicazioni in una sola operazione.',
        ],
        [
            'route' => 'movimenti.carico',
            'icon' => '⬆️',
            'label' => 'Carichi',
            'description' => 'Registra carichi da produzione interna o da terzisti.',
        ],
        [
            'route' => 'movimenti.scarico',
            'icon' => '⬇️',
            'label' => 'Prelievi & Resi',
            'description' => 'Prelievi rapidi con tracciamento operatore e commessa.',
        ],
        [
            'route' => 'conto-lavoro.wizard',
            'icon' => '🧵',
            'label' => 'Conto lavoro',
            'description' => 'Invii e rientri dai terzisti con gestione componenti.',
        ],
    ];
    $isMobile = $variant === 'mobile';
@endphp
<nav {{ $attributes->class([$isMobile ? 'space-y-3' : 'space-y-1']) }}>
    @foreach ($items as $item)
        @php($active = request()->routeIs($item['route']))
        <a
            href="{{ route($item['route']) }}"
            class="flex items-start gap-3 rounded-xl transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500
                {{ $isMobile
                    ? 'bg-white/70 dark:bg-slate-900/70 px-3 py-3 shadow-sm'
                    : 'px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-800'.($active ? ' bg-slate-100 dark:bg-slate-800' : '') }}"
        >
            <span class="text-xl leading-none">{{ $item['icon'] }}</span>
            <span class="flex-1">
                <span class="block text-sm font-semibold">{{ $item['label'] }}</span>
                <span class="block text-xs text-slate-500 dark:text-slate-400 {{ $isMobile ? '' : 'mt-0.5' }}">{{ $item['description'] }}</span>
            </span>
        </a>
    @endforeach
</nav>
