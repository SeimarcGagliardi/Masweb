@props(['color' => 'slate'])
@php
    $palettes = [
        'slate' => 'bg-slate-100 text-slate-800 dark:bg-slate-900/40 dark:text-slate-200',
        'emerald' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
        'amber' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-100',
        'rose' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200',
        'sky' => 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-200',
        'violet' => 'bg-violet-100 text-violet-800 dark:bg-violet-900/40 dark:text-violet-200',
    ];
    $classes = $palettes[$color] ?? $palettes['slate'];
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium $classes"]) }}>
  {{ $slot }}
</span>
