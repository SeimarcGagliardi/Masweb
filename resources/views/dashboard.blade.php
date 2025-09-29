@extends('layouts.app')
@section('title','Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
  <a href="{{ route('movimenti.transfer') }}" class="tile">
    <div class="text-3xl">🔁</div>
    <div class="tile-title">Trasferimenti</div>
    <div class="tile-sub">Sposta merce tra magazzini in un’unica operazione.</div>
    <div class="mt-3"><x-ui.button>Apri</x-ui.button></div>
  </a>

  <div class="tile opacity-70">
    <div class="text-3xl">⬆️</div>
    <div class="tile-title">Carico</div>
    <div class="tile-sub">Prossimo blocco</div>
  </div>

  <div class="tile opacity-70">
    <div class="text-3xl">⬇️</div>
    <div class="tile-title">Scarico/Prelievi</div>
    <div class="tile-sub">Prossimo blocco</div>
  </div>
</div>
@endsection
