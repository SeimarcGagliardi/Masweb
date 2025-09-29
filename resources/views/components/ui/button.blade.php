@props(['variant' => 'primary', 'type' => 'button'])
@php
  $classes = match($variant){
    'secondary' => 'btn-secondary',
    'ghost' => 'btn-ghost',
    default => 'btn-primary',
  };
@endphp
<button {{ $attributes->merge(['class' => $classes, 'type' => $type]) }}>
  {{ $slot }}
</button>
