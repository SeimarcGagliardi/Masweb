@extends('layouts.app')
@section('title','Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
  <a href="{{ route('movimenti.transfer') }}" class="tile">
    <div class="flex items-start justify-between">
      <div>
        <div class="tile-title">Trasferimenti rapidi</div>
        <div class="tile-sub">Sposta merce tra magazzini e ubicazioni in un flusso guidato.</div>
      </div>
      <div class="text-3xl" aria-hidden="true">🔁</div>
    </div>
    <div class="mt-4"><x-ui.button>Avvia trasferimento</x-ui.button></div>
  </a>

  <a href="{{ route('movimenti.carico') }}" class="tile">
    <div class="flex items-start justify-between">
      <div>
        <div class="tile-title">Carichi di magazzino</div>
        <div class="tile-sub">Gestisci carichi da produzione interna o da rientro terzisti.</div>
      </div>
      <div class="text-3xl" aria-hidden="true">⬆️</div>
    </div>
    <div class="mt-4"><x-ui.button>Registra carico</x-ui.button></div>
  </a>

  <a href="{{ route('movimenti.scarico') }}" class="tile">
    <div class="flex items-start justify-between">
      <div>
        <div class="tile-title">Prelievi &amp; resi</div>
        <div class="tile-sub">Prelievo guidato con commessa e reinserimento rapido dei resi.</div>
      </div>
      <div class="text-3xl" aria-hidden="true">⬇️</div>
    </div>
    <div class="mt-4"><x-ui.button>Gestisci prelievo</x-ui.button></div>
  </a>

  <a href="{{ route('conto-lavoro.wizard') }}" class="tile">
    <div class="flex items-start justify-between">
      <div>
        <div class="tile-title">Conto lavoro</div>
        <div class="tile-sub">Invii e rientri dai terzisti con controllo avanzamento.</div>
      </div>
      <div class="text-3xl" aria-hidden="true">🧵</div>
    </div>
    <div class="mt-4"><x-ui.button>Apri modulo</x-ui.button></div>
  </a>
</div>
@endsection
