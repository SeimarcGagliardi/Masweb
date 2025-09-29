{{-- resources/views/livewire/movimenti/transfer-wizard.blade.php --}}
<div class="mx-auto max-w-5xl p-4 space-y-6">
  <h1 class="text-2xl font-semibold">Trasferimento tra magazzini</h1>

  {{-- Stepper --}}
  <div class="flex items-center gap-2 text-sm">
    @foreach ([1=>'Origine',2=>'Destinazione',3=>'Articoli',4=>'Riepilogo',5=>'Conferma'] as $i=>$label)
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $step >= $i ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">{{ $i }}</div>
        <span class="{{ $step >= $i ? 'font-medium' : 'text-gray-500' }}">{{ $label }}</span>
      </div>
      @if($i<5)<div class="flex-1 h-px bg-gray-200"></div>@endif
    @endforeach
  </div>

  {{-- Step 1: Origine --}}
  @if($step===1)
  <div class="bg-white rounded-2xl shadow p-4 space-y-3">
    <label class="block text-sm font-medium">Magazzino di origine</label>
    <select wire:model="origine.magazzino_id" class="w-full border rounded-lg p-2">
      <option value="">— seleziona —</option>
      @foreach($magazzini as $m)
        <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
      @endforeach
    </select>
    @error('origine.magazzino_id')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
  </div>
  @endif

  {{-- Step 2: Destinazione --}}
  @if($step===2)
  <div class="bg-white rounded-2xl shadow p-4 space-y-3">
    <label class="block text-sm font-medium">Magazzino di destinazione</label>
    <select wire:model="destinazione.magazzino_id" class="w-full border rounded-lg p-2">
      <option value="">— seleziona —</option>
      @foreach($magazzini as $m)
        <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
      @endforeach
    </select>
    @error('destinazione.magazzino_id')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
  </div>
  @endif

  {{-- Step 3: Articoli --}}
  @if($step===3)
  <div class="bg-white rounded-2xl shadow p-4 space-y-4">
    <div class="flex justify-between items-center">
      <h2 class="font-medium">Articoli da trasferire</h2>
      <button wire:click="addRiga" type="button" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200">+ Aggiungi riga</button>
    </div>
    @foreach($righe as $i => $r)
      <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-end">
        <div class="md:col-span-6">
          <label class="block text-sm">Articolo</label>
          <select wire:model="righe.{{ $i }}.articolo_id" class="w-full border rounded-lg p-2">
            <option value="">— seleziona —</option>
            @foreach($articoli as $a)
              <option value="{{ $a->id }}">{{ $a->codice }} — {{ $a->descrizione }}</option>
            @endforeach
          </select>
          @error("righe.$i.articolo_id")<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="md:col-span-3">
          <label class="block text-sm">Q.tà</label>
          <input type="number" step="0.001" min="0" wire:model.lazy="righe.{{ $i }}.qta" class="w-full border rounded-lg p-2" />
          @error("righe.$i.qta")<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm">Lotto (opz.)</label>
          <input type="text" wire:model.lazy="righe.{{ $i }}.lotto" class="w-full border rounded-lg p-2" />
        </div>
        <div class="md:col-span-1">
          <button type="button" wire:click="removeRiga({{ $i }})" class="w-full border rounded-lg p-2 hover:bg-red-50">🗑️</button>
        </div>
      </div>
      <hr class="my-2">
    @endforeach
  </div>
  @endif

  {{-- Step 4: Riepilogo --}}
  @if($step===4)
  <div class="bg-white rounded-2xl shadow p-4 space-y-4">
    <p class="text-sm">Controlla i dati e procedi alla conferma.</p>
    <ul class="text-sm space-y-1">
      <li><strong>Origine:</strong> {{ optional($magazzini->firstWhere('id', $origine['magazzino_id']))?->descrizione }}</li>
      <li><strong>Destinazione:</strong> {{ optional($magazzini->firstWhere('id', $destinazione['magazzino_id']))?->descrizione }}</li>
    </ul>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead><tr class="border-b">
          <th class="text-left py-2">Articolo</th><th class="text-right">Q.tà</th><th class="text-left">Lotto</th>
        </tr></thead>
        <tbody>
          @foreach($riepilogo['righe'] ?? [] as $r)
            <tr class="border-b">
              <td class="py-2">{{ $r['codice'] }} — {{ $r['descr'] }}</td>
              <td class="text-right">{{ $r['qta'] }}</td>
              <td>{{ $r['lotto'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

  {{-- Navigazione --}}
  <div class="flex justify-between">
    <button type="button" wire:click="back" @disabled($step===1)
      class="px-4 py-2 rounded-lg border">Indietro</button>

    @if($step<4)
      <button type="button" wire:click="next" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Avanti</button>
    @elseif($step===4)
    <button type="button" wire:click="conferma" wire:loading.attr="disabled" class="px-4 py-2 rounded-lg bg-green-600 text-white">
        <span wire:loading.remove>Conferma trasferimento</span>
        <span wire:loading>Salvo…</span>
    </button>

    @endif
  </div>
  @if ($errors->has('general'))
  <div class="p-3 rounded-lg bg-red-50 text-red-800 mt-3">
    {{ $errors->first('general') }}
  </div>
@endif
  @if (session('ok'))
    <div class="p-3 rounded-lg bg-green-50 text-green-800">{{ session('ok') }}</div>
  @endif
</div>
