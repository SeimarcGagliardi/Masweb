<div class="mx-auto max-w-6xl p-4 space-y-6">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h1 class="text-2xl font-semibold">Gestione conto lavoro</h1>
    <div class="flex overflow-hidden rounded-full border border-slate-200 bg-white/70 shadow-sm backdrop-blur dark:border-slate-700">
      <button
        type="button"
        wire:click="switchFase('invio')"
        class="px-4 py-2 text-sm font-medium transition {{ $fase === 'invio' ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
        Invio ai terzisti
      </button>
      <button
        type="button"
        wire:click="switchFase('rientro')"
        class="px-4 py-2 text-sm font-medium transition {{ $fase === 'rientro' ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
        Rientro lavorazioni
      </button>
    </div>
  </div>

  @php($labels = $fase === 'invio' ? [1=>'Dati invio',2=>'Articoli',3=>'Riepilogo'] : [1=>'Ordine e magazzino',2=>'Quantità rientro',3=>'Riepilogo'])

  <div class="rounded-3xl border border-slate-200 bg-white/70 p-4 shadow-sm backdrop-blur">
    <ol class="flex flex-col gap-4 text-sm md:flex-row md:items-center">
      @foreach ($labels as $i => $label)
        <li class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-full border transition-all duration-200 {{ $step >= $i ? 'border-violet-500 bg-violet-500 text-white shadow-inner' : 'border-slate-200 bg-slate-100 text-slate-500' }}">
            {{ $i }}
          </div>
          <div class="flex flex-col">
            <span class="font-semibold {{ $step >= $i ? 'text-slate-900' : 'text-slate-500' }}">{{ $label }}</span>
            <span class="text-xs text-slate-400">Passo {{ $i }} di {{ count($labels) }}</span>
          </div>
        </li>
        @if($i < count($labels))
          <div class="hidden flex-1 md:block">
            <div class="h-0.5 rounded-full bg-gradient-to-r from-violet-200 via-violet-500 to-violet-200 opacity-70"></div>
          </div>
        @endif
      @endforeach
    </ol>
  </div>

  @if($fase === 'invio')
    @if($step == 1)
      <div class="space-y-6 rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
        <div>
          <label class="block text-sm font-medium">Terzista</label>
          <select wire:model="invio.terzista_id" class="w-full border rounded-xl p-2">
            <option value="">— seleziona —</option>
            @foreach($terzisti as $t)
              <option value="{{ $t->id }}">{{ $t->ragione_sociale }}</option>
            @endforeach
          </select>
          @error('invio.terzista_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Magazzino di uscita</label>
            <select wire:model="invio.magazzino_id" class="w-full border rounded-xl p-2">
              <option value="">— seleziona —</option>
              @foreach($magazzini as $m)
                <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
              @endforeach
            </select>
            @error('invio.magazzino_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium">Ubicazione</label>
            <select wire:model="invio.ubicazione_id" class="w-full border rounded-xl p-2">
              <option value="">— seleziona —</option>
              @foreach($ubicazioniInvio as $u)
                <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
              @endforeach
            </select>
            @error('invio.ubicazione_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm">Data invio</label>
            <input type="date" wire:model="invio.data_invio" class="w-full border rounded-xl p-2" />
            @error('invio.data_invio')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm">Data rientro prevista</label>
            <input type="date" wire:model="invio.data_rientro_prevista" class="w-full border rounded-xl p-2" />
            @error('invio.data_rientro_prevista')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
          </div>
        </div>
        <div>
          <label class="block text-sm">Note per il terzista</label>
          <textarea wire:model.lazy="invio.note" rows="3" class="w-full border rounded-xl p-2" placeholder="Indicazioni, componenti, urgenze..."></textarea>
        </div>
      </div>
    @endif

    @if($step == 2)
      <div class="space-y-5 rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
        <div class="flex items-center justify-between">
          <h2 class="font-medium">Articoli da inviare</h2>
          <button type="button" class="btn-secondary" wire:click="addRiga">+ Aggiungi riga</button>
        </div>
        @foreach($righe as $i => $riga)
          <div class="grid grid-cols-1 items-end gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 lg:grid-cols-12">
            <div class="lg:col-span-5">
              <label class="block text-sm">Articolo</label>
              <select wire:model="righe.{{ $i }}.articolo_id" class="w-full border rounded-xl p-2">
                <option value="">— seleziona —</option>
                @foreach($articoli as $a)
                  <option value="{{ $a->id }}">{{ $a->codice }} — {{ $a->descrizione }}</option>
                @endforeach
              </select>
              @error("righe.$i.articolo_id")<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="lg:col-span-2">
              <label class="block text-sm">Q.tà</label>
              <input type="number" step="0.001" min="0" wire:model.lazy="righe.{{ $i }}.qta" class="w-full border rounded-xl p-2" />
              @error("righe.$i.qta")<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="lg:col-span-2">
              <label class="block text-sm">Lotto</label>
              <input type="text" wire:model.lazy="righe.{{ $i }}.lotto" class="w-full border rounded-xl p-2" />
            </div>
            <div class="lg:col-span-3">
              <label class="block text-sm">Componenti / lavorazioni</label>
              <input type="text" wire:model.lazy="righe.{{ $i }}.componenti" class="w-full border rounded-xl p-2" placeholder="Es. fodera, bottoni..." />
            </div>
          </div>
          <hr class="border-dashed">
        @endforeach
      </div>
    @endif

    @if($step == 3)
      <div class="space-y-5 rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
        <h2 class="font-medium">Riepilogo invio</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
          <div>
            <div class="font-semibold">Terzista</div>
            <div>{{ $riepilogo['terzista'] ?? '—' }}</div>
          </div>
          <div>
            <div class="font-semibold">Magazzino</div>
            <div>{{ $riepilogo['magazzino'] ?? '—' }}</div>
            @if($riepilogo['ubicazione'] ?? false)
              <div class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['ubicazione'] }}</div>
            @endif
          </div>
          <div>
            <div class="font-semibold">Data invio</div>
            <div>{{ $invio['data_invio'] }}</div>
          </div>
          <div>
            <div class="font-semibold">Rientro previsto</div>
            <div>{{ $invio['data_rientro_prevista'] ?: '—' }}</div>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="border-b">
                <th class="text-left py-2">Articolo</th>
                <th class="text-right">Q.tà</th>
                <th class="text-left">Lotto</th>
                <th class="text-left">Componenti</th>
              </tr>
            </thead>
            <tbody>
              @foreach($riepilogo['righe'] ?? [] as $r)
                <tr class="border-b">
                  <td class="py-2">{{ $r['codice'] }} — {{ $r['descrizione'] }}</td>
                  <td class="text-right">{{ $r['qta'] }}</td>
                  <td>{{ $r['lotto'] ?: '—' }}</td>
                  <td>{{ $r['componenti'] ?: '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  @else
    @if($step == 1)
      <div class="space-y-6 rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
        <div>
          <label class="block text-sm font-medium">Ordine conto lavoro</label>
          <select wire:model="rientro.ordine_id" class="w-full border rounded-xl p-2">
            <option value="">— seleziona —</option>
            @foreach($ordiniAperti as $ordine)
              <option value="{{ $ordine->id }}">#{{ $ordine->id }} — {{ $ordine->terzista?->ragione_sociale }} ({{ $ordine->stato }})</option>
            @endforeach
          </select>
          @error('rientro.ordine_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Magazzino di rientro</label>
            <select wire:model="rientro.magazzino_id" class="w-full border rounded-xl p-2">
              <option value="">— seleziona —</option>
              @foreach($magazzini as $m)
                <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
              @endforeach
            </select>
            @error('rientro.magazzino_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium">Ubicazione</label>
            <select wire:model="rientro.ubicazione_id" class="w-full border rounded-xl p-2">
              <option value="">— seleziona —</option>
              @foreach($ubicazioniRientro as $u)
                <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
              @endforeach
            </select>
            @error('rientro.ubicazione_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
          </div>
        </div>
        <div>
          <label class="block text-sm">Note interne</label>
          <textarea wire:model.lazy="rientro.note" rows="3" class="w-full border rounded-xl p-2"></textarea>
        </div>
      </div>
    @endif

    @if($step == 2)
      <div class="space-y-5 rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
        <h2 class="font-medium">Quantità da rientrare</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="border-b">
                <th class="text-left py-2">Riga</th>
                <th class="text-right">Inviato</th>
                <th class="text-right">Disponibile</th>
                <th class="text-right">Rientro</th>
                <th class="text-right">Scarto</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rientroRighe as $i => $riga)
                <tr class="border-b">
                  <td class="py-2">
                    <div>{{ $riga['articolo'] }}</div>
                    @if($riga['lotto'])<div class="text-xs text-slate-500">Lotto: {{ $riga['lotto'] }}</div>@endif
                  </td>
                  <td class="text-right">{{ $riga['qta_inviata'] }}</td>
                  <td class="text-right">{{ $riga['disponibile'] }}</td>
                  <td class="text-right">
                    <input type="number" step="0.001" min="0" max="{{ $riga['disponibile'] }}" wire:model.lazy="rientroRighe.{{ $i }}.qta_rientro" class="w-full border rounded-xl p-1.5" />
                    @error("rientroRighe.$i.qta_rientro")<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                  </td>
                  <td class="text-right">
                    <input type="number" step="0.001" min="0" wire:model.lazy="rientroRighe.{{ $i }}.scarto" class="w-full border rounded-xl p-1.5" />
                    @error("rientroRighe.$i.scarto")<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif

    @if($step == 3)
      <div class="space-y-5 rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
        <h2 class="font-medium">Riepilogo rientro</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
          <div>
            <div class="font-semibold">Ordine</div>
            <div>#{{ $riepilogo['ordine'] ?? '—' }}</div>
          </div>
          <div>
            <div class="font-semibold">Magazzino</div>
            <div>{{ $riepilogo['magazzino'] ?? '—' }}</div>
            @if($riepilogo['ubicazione'] ?? false)
              <div class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['ubicazione'] }}</div>
            @endif
          </div>
          <div>
            <div class="font-semibold">Terzista</div>
            <div>{{ $riepilogo['terzista'] ?? '—' }}</div>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="border-b">
                <th class="text-left py-2">Articolo</th>
                <th class="text-right">Rientro</th>
                <th class="text-right">Scarto</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rientroRighe as $r)
                @if((float)($r['qta_rientro'] ?? 0) > 0 || (float)($r['scarto'] ?? 0) > 0)
                  <tr class="border-b">
                    <td class="py-2">{{ $r['articolo'] }}</td>
                    <td class="text-right">{{ $r['qta_rientro'] }}</td>
                    <td class="text-right">{{ $r['scarto'] }}</td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  @endif

  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <button type="button" class="btn-secondary" wire:click="back" @disabled($step==1)>Indietro</button>
    @if($step < 3)
      <button type="button" class="btn-primary" wire:click="next">Avanti</button>
    @else
      <button type="button" class="btn-primary" wire:click="conferma" wire:loading.attr="disabled">
        <span wire:loading.remove>Conferma</span>
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
