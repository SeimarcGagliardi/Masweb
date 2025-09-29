@props(['color' => 'slate'])
<span {{ $attributes->merge(['class'=>"inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900/40 dark:text-{$color}-200"]) }}>
  {{ $slot }}
</span>
