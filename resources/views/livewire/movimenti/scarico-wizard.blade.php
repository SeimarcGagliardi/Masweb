<div class="mx-auto max-w-5xl p-4 space-y-6">
  <h1 class="text-2xl font-semibold">Prelievo / Reso magazzino</h1>

  @php
    $wizardSteps = [
      1 => 'Contesto',
      2 => 'Articoli',
      3 => 'Riepilogo',
    ];
  @endphp

  <div class="rounded-3xl border border-slate-200 bg-white/70 p-4 shadow-sm backdrop-blur">
    <ol class="flex flex-col gap-4 text-sm md:flex-row md:items-center">
      @foreach($wizardSteps as $i => $label)
        <li class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-full border transition-all duration-200 {{ $step >= $i ? 'border-amber-500 bg-amber-500 text-white shadow-inner' : 'border-slate-200 bg-slate-100 text-slate-500' }}">
            {{ $i }}
          </div>
          <div class="flex flex-col">
            <span class="font-semibold {{ $step >= $i ? 'text-slate-900' : 'text-slate-500' }}">{{ $label }}</span>
            <span class="text-xs text-slate-400">Passo {{ $i }} di {{ count($wizardSteps) }}</span>
          </div>
        </li>
        @if($i < count($wizardSteps))
          <div class="hidden flex-1 md:block">
            <div class="h-0.5 rounded-full bg-gradient-to-r from-amber-200 via-amber-500 to-amber-200 opacity-70"></div>
          </div>
        @endif
      @endforeach
    </ol>
  </div>

  @if($step == 1)
    <div class="space-y-6 rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
      <div class="flex items-center gap-3">
        <label class="text-sm font-medium">Operazione</label>
        <div class="flex items-center gap-2">
          <label class="inline-flex items-center gap-2 text-sm">
            <input type="radio" wire:model="contesto.tipo" value="prelievo" class="accent-brand-600">
            <span>Prelievo</span>
          </label>
          <label class="inline-flex items-center gap-2 text-sm">
            <input type="radio" wire:model="contesto.tipo" value="reso" class="accent-brand-600">
            <span>Reso</span>
          </label>
        </div>
      </div>
      @error('contesto.tipo')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

      <div>
        <label class="block text-sm font-medium">Magazzino</label>
        <select wire:model="contesto.magazzino_id" class="w-full border rounded-xl p-2">
          <option value="">— seleziona —</option>
          @foreach($magazzini as $m)
            <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
          @endforeach
        </select>
        @error('contesto.magazzino_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      @if($ubicazioni->isNotEmpty())
        <div>
          <label class="block text-sm font-medium">Ubicazione</label>
          <select wire:model="contesto.ubicazione_id" class="w-full border rounded-xl p-2">
            <option value="">— seleziona —</option>
            @foreach($ubicazioni as $u)
              <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
            @endforeach
          </select>
          @error('contesto.ubicazione_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
      @elseif($contesto['magazzino_id'])
        <p class="text-xs text-slate-500">Non ci sono ubicazioni attive per il magazzino selezionato.</p>
      @endif

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm">Operatore</label>
          <input type="text" wire:model.lazy="contesto.operatore" class="w-full border rounded-xl p-2" />
          @error('contesto.operatore')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm">Commessa</label>
          <input type="text" wire:model.lazy="contesto.commessa" class="w-full border rounded-xl p-2" />
        </div>
        <div>
          <label class="block text-sm">Destinatario / Reparto</label>
          <input type="text" wire:model.lazy="contesto.destinatario" class="w-full border rounded-xl p-2" placeholder="Es. sartoria, terzista..." />
        </div>
      </div>

      <div>
        <label class="block text-sm">Note</label>
        <textarea wire:model.lazy="contesto.note" rows="3" class="w-full border rounded-xl p-2" placeholder="Dettagli aggiuntivi"></textarea>
      </div>
    </div>
  @endif

  @if($step == 2)
    <div class="space-y-5 rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
      <div class="flex items-center justify-between">
        <h2 class="font-medium">Articoli movimentati</h2>
        <button type="button" wire:click="addRiga" class="btn-secondary">+ Aggiungi riga</button>
      </div>

      @foreach($righe as $i => $riga)
        <div class="grid grid-cols-1 items-end gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:grid-cols-12">
          <div class="md:col-span-6">
            <label class="block text-sm">Articolo</label>
            <select wire:model="righe.{{ $i }}.articolo_id" class="w-full border rounded-xl p-2">
              <option value="">— seleziona —</option>
              @foreach($articoli as $a)
                <option value="{{ $a->id }}">{{ $a->codice }} — {{ $a->descrizione }}</option>
              @endforeach
            </select>
            @error("righe.$i.articolo_id")<p class="text-sm text-red-600">{{ $message }}</p>@enderror
          </div>
          <div class="md:col-span-3">
            <label class="block text-sm">Q.tà</label>
            <input type="number" step="0.001" min="0" wire:model.lazy="righe.{{ $i }}.qta" class="w-full border rounded-xl p-2" />
            @error("righe.$i.qta")<p class="text-sm text-red-600">{{ $message }}</p>@enderror
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm">Lotto (se presente)</label>
            <input type="text" wire:model.lazy="righe.{{ $i }}.lotto" class="w-full border rounded-xl p-2" />
          </div>
          <div class="md:col-span-1">
            <button type="button" wire:click="removeRiga({{ $i }})" class="w-full border rounded-xl p-2 hover:bg-red-50">🗑️</button>
          </div>
        </div>
        <hr class="border-dashed">
      @endforeach
    </div>
  @endif

  @if($step == 3)
    <div class="space-y-5 rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
      <h2 class="font-medium">Riepilogo</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
        <div>
          <div class="font-semibold">Operazione</div>
          <div class="uppercase tracking-wide text-xs font-semibold"><x-ui.badge color="amber">{{ strtoupper($contesto['tipo']) }}</x-ui.badge></div>
        </div>
        <div>
          <div class="font-semibold">Magazzino</div>
          <div>{{ $riepilogo['magazzino'] ?? '—' }}</div>
          @if($riepilogo['ubicazione'] ?? false)
            <div class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['ubicazione'] }}</div>
          @endif
        </div>
        <div>
          <div class="font-semibold">Operatore</div>
          <div>{{ $contesto['operatore'] }}</div>
        </div>
        <div>
          <div class="font-semibold">Commessa</div>
          <div>{{ $contesto['commessa'] ?: '—' }}</div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="border-b">
              <th class="text-left py-2">Articolo</th>
              <th class="text-right">Q.tà</th>
              <th class="text-left">Lotto</th>
            </tr>
          </thead>
          <tbody>
            @foreach($riepilogo['righe'] ?? [] as $r)
              <tr class="border-b">
                <td class="py-2">{{ $r['codice'] }} — {{ $r['descrizione'] }}</td>
                <td class="text-right">{{ $r['qta'] }}</td>
                <td>{{ $r['lotto'] ?: '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <button type="button" class="btn-secondary" wire:click="back" @disabled($step==1)>Indietro</button>
    @if($step < 3)
      <button type="button" class="btn-primary" wire:click="next">Avanti</button>
    @else
      <button type="button" class="btn-primary" wire:click="conferma" wire:loading.attr="disabled">
        <span wire:loading.remove>Conferma operazione</span>
        <span wire:loading>Salvataggio…</span>
      </button>
    @endif
  </div>

  @if ($errors->has('general'))
    <div class="p-3 rounded-xl bg-red-50 text-red-800">{{ $errors->first('general') }}</div>
  @endif
  @if (session('ok'))
    <div class="p-3 rounded-xl bg-green-50 text-green-800">{{ session('ok') }}</div>
  @endif
</div>
